<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use App\Models\Invoice;

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
            'supplier.string' => 'Le fournisseur doit être une chaîne de caractères.',
            'supplier.exists' => 'Le fournisseur n\'existe pas.',
            'order.required' => 'L\'ordre est obligatoire.',
            'order.in' => 'L\'ordre doit être soit "desc" soit "asc".',
            'status.in' => 'Le statut doit être soit "all", "settled" ou "not-settled".',
            'type_date.in' => 'Le type de date doit être soit "all", "day", "week", "month" ou "year".',
            'day.date' => 'Le champ jour doit être une date valide.',
            'day.before_or_equal' => 'Le champ jour doit être une date antérieure ou égale à aujourd\'hui.',
            'week.regex' => 'Le champ semaine doit être au format "YYYY-WW".',
            'week.before_or_equal' => 'Le champ semaine doit être une date antérieure ou égale à aujourd\'hui.',
            'month.date_format' => 'Le champ mois doit être au format "YYYY-MM".',
            'month.before_or_equal' => 'Le champ mois doit être une date antérieure ou égale à aujourd\'hui.',
            'year.integer' => 'Le champ année doit être un entier.',
            'year.min' => 'Le champ année doit être au minimum 1900.',
            'year.max' => 'Le champ année ne peut pas être supérieur à l\'année en cours.',
        ]);

        // Ajout de la vérification qu'un seul champ est rempli
        $validator->after(function ($validator) use ($request) {
            $fields = ['day', 'week', 'month', 'year', 'all'];
            $filledFields = array_filter($fields, fn($field) => !empty($request->$field));
            if (count($filledFields) > 1) {
                $validator->errors()->add('fields', 'Vous ne pouvez remplir qu\'un seul champ de recherche (jour, semaine, mois, année ou "tout").');
            }
        });

        $messages = [
            'day.required' => 'Le champ jour est obligatoire lorsque le type de date est "jour".',
            'week.required' => 'Le champ semaine est obligatoire lorsque le type de date est "semaine".',
            'month.required' => 'Le champ mois est obligatoire lorsque le type de date est "mois".',
            'year.required' => 'Le champ année est obligatoire lorsque le type de date est "année".',
        ];
        
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

        // Filtrer par fournisseur
        $supplies = $warehouse->supplies;

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
        return view('pages.warehouse.invoice.info');
    }

    public function settleInvoice(int $invoice_id)
    {
        return view('pages.warehouse.invoice.settle');
    }
}
