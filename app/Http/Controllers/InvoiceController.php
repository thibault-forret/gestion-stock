<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function invoiceList()
    {
        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Récupère toutes les factures de l'entrepôt
        $invoices = $warehouse->supplies->flatMap(function ($supply) {
            return $supply->invoice()->get();
        });

        $invoices = $invoices->sortByDesc('created_at');

        $suppliers = Supplier::all();

        return view('pages.warehouse.invoice.list', compact('invoices', 'suppliers'));
    }

    public function filterInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier' => 'nullable|string|exists:suppliers,supplier_name',
            'order' => 'required|in:desc,asc',
            'status' => 'nullable|in:all,settled,not-settled',
            'type_date' => 'required|in:all,day,week,month,year',
            'day' => 'nullable|date|before_or_equal:today',
            'week' => 'nullable|regex:/^\d{4}-W\d{2}$/|before_or_equal:today',
            'month' => 'nullable|date_format:Y-m|before_or_equal:today',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ], [
            'supplier.string' => __('messages.validate.supplier_string'),
            'supplier.exists' => __('messages.validate.supplier_name_exists'),
            'order.required' => __('messages.validate.order_required'),
            'order.in' => __('messages.validate.order_in'),
            'status.in' => __('messages.validate.status_in'),
            'type_date.required' => __('messages.validate.type_date_required'),
            'type_date.in' => __('messages.validate.type_date_in'),
            'day.date' => __('messages.validate.day_date'),
            'day.before_or_equal' => __('messages.validate.day_before_or_equal'),
            'week.regex' => __('messages.validate.week_regex'),
            'week.before_or_equal' => __('messages.validate.week_before_or_equal'),
            'month.date_format' => __('messages.validate.month_date_format'),
            'month.before_or_equal' => __('messages.validate.month_before_or_equal'),
            'year.integer' => __('messages.validate.year_integer'),
            'year.min' => __('messages.validate.year_min'),
            'year.max' => __('messages.validate.year_max'),
        ]);

        // Vérification qu'un seul champ est rempli (type de date)
        $validator->after(function ($validator) use ($request) {
            $fields = ['day', 'week', 'month', 'year', 'all'];
            $filledFields = array_filter($fields, fn($field) => !empty($request->$field));
            if (count($filledFields) > 1) {
                $validator->errors()->add('fields', __('messages.validate.invoice_only_one_field'));
            }
        });

        $messages = [
            'day.required' => __('messages.validate.day_required'),
            'week.required' => __('messages.validate.week_required'),
            'month.required' => __('messages.validate.month_required'),
            'year.required' => __('messages.validate.year_required'),
        ];
        
        // Vérification des champs en fonction du type de date
        Validator::make($request->all(), [], $messages)
            ->sometimes('day', 'required', fn($input) => $input->type_date === 'day')
            ->sometimes('week', 'required', fn($input) => $input->type_date === 'week')
            ->sometimes('month', 'required', fn($input) => $input->type_date === 'month')
            ->sometimes('year', 'required', fn($input) => $input->type_date === 'year')
            ->validate();        

        // Réduit le tableau aux éléments non null
        $data = array_filter($request->only(['supplier', 'order', 'status', 'type_date', 'day', 'week', 'month', 'year']), function ($value) {
            return $value !== null && $value !== '';
        });

        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        $supplies = $warehouse->supplies;

        // Filtrer par fournisseur
        if (!empty($data['supplier']) && $data['supplier'] !== 'all') {
            $supplier = Supplier::where('supplier_name', $data['supplier'])->first();
            if ($supplier) {
                $supplies = $supplies->where('supplier_id', $supplier->id);
            }
        }

        // Récupère les factures en fonction des critères
        $invoices = $supplies->flatMap(function ($supply) use ($data) {
            // Appliquer les filtres sur la relation invoice
            $query = $supply->invoice()->orderBy('created_at', $data['order']);

            // Filtrer par statut
            switch ($data['status']) {
                case 'settled':
                    $query->where('invoice_status', Invoice::INVOICE_STATUS_PAID);
                    break;
                case 'not-settled':
                    $query->where('invoice_status', Invoice::INVOICE_STATUS_UNPAID);
                    break;
                default:
                    break;
            }

            // Filtrer par type de date
            switch ($data['type_date']) {
                case 'day':
                    $query->whereDate('created_at', $data['day']);
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        date('Y-m-d', strtotime($data['week'] . ' Monday')),
                        date('Y-m-d', strtotime($data['week'] . ' Sunday')),
                    ]);
                    break;
                case 'month':
                    $query->whereYear('created_at', substr($data['month'], 0, 4))
                        ->whereMonth('created_at', substr($data['month'], 5, 2));
                    break;
                case 'year':
                    $query->whereYear('created_at', $data['year']);
                    break;
                default:
                    break;
            }

            // Récupérer les factures
            return $query->get();
        });

        // Trier les factures par date (ascendant ou descendant)
        $invoices = $data['order'] === 'asc'
            ? $invoices->sortBy('created_at')
            : $invoices->sortByDesc('created_at');

        $suppliers = Supplier::all();

        return view('pages.warehouse.invoice.list', compact('invoices', 'suppliers'));
    }

    public function infoInvoice(int $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        if (!$invoice) {
            return redirect()->route('warehouse.invoice.list')->with('error', __('messages.invoice_not_found'));
        }

        $supply = $invoice->supply;

        $total_amount = $supply->supplyLines->sum(fn($supply_line) => $supply_line->unit_price * $supply_line->quantity_supplied);

        return view('pages.warehouse.invoice.info', compact('invoice', 'supply', 'total_amount'));
    }

    public function settleInvoice(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
        ], [
            'invoice_id.required' => __('messages.validate.invoice_id_required'),
            'invoice_id.integer' => __('messages.validate.invoice_id_integer'),
            'invoice_id.exists' => __('messages.validate.invoice_not_found'),
        ]);

        $invoice_id = $request->input('invoice_id');

        $invoice = Invoice::find($invoice_id);

        // Vérifier si la facture n'est pas déjà réglée
        if ($invoice->invoice_status === Invoice::INVOICE_STATUS_PAID) {
            return redirect()->route('warehouse.invoice.list')->with('error', __('messages.invoice_already_settled'));
        }

        // Mettre à jour le statut de la facture
        $success = $invoice->update([
            'invoice_status' => Invoice::INVOICE_STATUS_PAID,
        ]);

        if ($success){
            return redirect()->route('warehouse.invoice.info', ['invoice_id' => $invoice_id])->with('success', __('messages.invoice_settled'));
        }
        else {
            return redirect()->route('warehouse.invoice.info', ['invoice_id' => $invoice_id])->with('error', __('messages.invoice_not_settled'));
        }
    }

    public function showInvoice(int $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        if (!$invoice) {
            return redirect()->route('warehouse.invoice.list')->with('error', __('messages.invoice_not_found'));
        }

        $supply = $invoice->supply;

        $total_amount = $supply->supplyLines->sum(fn($supply_line) => $supply_line->unit_price * $supply_line->quantity_supplied);

        $warehouse_name = $supply->warehouse->warehouse_name;

        $pdf = Pdf::loadView('pages.warehouse.invoice.pdf', compact('invoice', 'supply', 'total_amount', 'warehouse_name'));

        // Pour afficher le PDF dans le navigateur
        return $pdf->stream(str_replace(' ', '_', $warehouse_name).'_INVOICE_'.$invoice->invoice_number.'_'.$invoice->created_at.'.pdf');
    }

    public function downloadInvoice(int $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);

        if (!$invoice) {
            return redirect()->route('warehouse.invoice.list')->with('error', __('messages.invoice_not_found'));
        }

        $supply = $invoice->supply;

        $total_amount = $supply->supplyLines->sum(fn($supply_line) => $supply_line->unit_price * $supply_line->quantity_supplied);

        $warehouse_name = $supply->warehouse->warehouse_name;

        $pdf = Pdf::loadView('pages.warehouse.invoice.pdf', compact('invoice', 'supply', 'total_amount', 'warehouse_name'));

        // Pour afficher le PDF dans le navigateur
        return $pdf->download(str_replace(' ', '_', $warehouse_name).'_INVOICE_'.$invoice->invoice_number.'_'.$invoice->created_at.'.pdf');
    }
}
