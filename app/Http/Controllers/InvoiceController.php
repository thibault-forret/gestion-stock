<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Store;
use DateTime;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('pages.warehouse.invoice.index');
    }

    public function invoiceListOrder()
    {
        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Récupère toutes les factures de l'entrepôt
        $invoices = $warehouse->stores->flatMap(function ($store) {
            return $store->orders->flatMap(function ($order) {
                return $order->invoice ? [$order->invoice] : [];
            });
        });

        $stores = $warehouse->stores;

        $invoices = $invoices->sortByDesc('created_at');

        return view('pages.warehouse.invoice.list_order', compact('invoices', 'stores'));
    }

    public function infoInvoiceOrder(string $invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->first();

        if (!$invoice) {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        $order = $invoice->order;

        // Vérifier si la facture est une facture de commande ou de fourniture
        if (!$order) 
        {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        $warehouse = $order->store->warehouse;
        
        $total_amount_ht = $order->calculateTotalPrice();
        $total_amount_ttc = $total_amount_ht * $warehouse->global_margin;

        return view('pages.warehouse.invoice.info_order', compact('invoice', 'order', 'warehouse', 'total_amount_ht', 'total_amount_ttc'));
    }

    public function invoiceListSupply()
    {
        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Récupère toutes les factures de l'entrepôt
        $invoices = $warehouse->supplies->flatMap(function ($supply) {
            return $supply->invoice()->get();
        });

        $invoices = $invoices->sortByDesc('created_at');

        $suppliers = Supplier::all();

        return view('pages.warehouse.invoice.list_supply', compact('invoices', 'suppliers'));
    }

    public function searchInvoice(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ], [
            'search.required' => __('messages.validate.search_required'),
            'search.string' => __('messages.validate.search_string'),
        ]);

        $invoice = Invoice::where('invoice_number', $request->input('search'))->first();

        if (!$invoice) {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        // Vérifier si la facture est une facture de commande ou de fourniture
        if ($invoice->order) {
            return redirect()->route('warehouse.invoice.info.order', ['invoice_number' => $invoice->invoice_number]);
        }
        else {
            return redirect()->route('warehouse.invoice.info.supply', ['invoice_number' => $invoice->invoice_number]);
        }
    }

    public function filterInvoiceOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store' => 'nullable|string|exists:stores,store_name',
            'order' => 'required|in:desc,asc',
            'status' => 'required|in:all,settled,not-settled',
            'type_date' => 'required|in:all,day,week,month,year',
            'day' => 'nullable|date|before_or_equal:today',
            'week' => 'nullable|regex:/^\d{4}-W\d{2}$/|before_or_equal:today',
            'month' => 'nullable|date_format:Y-m|before_or_equal:today',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ], [
            'store.string' => __('messages.validate.store_string'),
            'store.exists' => __('messages.validate.store_name_exists'),

            'order.required' => __('messages.validate.order_required'),
            'order.in' => __('messages.validate.order_in'),
            'status.required' => __('messages.validate.status_required'),
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

        $validator->after(function($validator) use ($request) {
            if ($request->order !== 'asc' && $request->order !== 'desc') {
                $validator->errors()->add('order', __('messages.validate.order_in'));
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

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
        $data = array_filter($request->only(['store', 'order', 'status', 'priority_level', 'type_date', 'day', 'week', 'month', 'year']), function ($value) {
            return $value !== null && $value !== '';
        });

        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        $stores = $warehouse->stores;

        $orders = $stores->flatMap(function ($store) {
            return $store->orders;
        });

        // Filtrer par fournisseur
        if (!empty($data['store']) && $data['store'] !== 'all') {
            $store = Store::where('store_name', $data['store'])->first();
            if ($store) {
                $orders = $store->orders;
            }
        }

        // Récupère les factures en fonction des critères
        $invoices = $orders->flatMap(function ($order) use ($data) {
            // Appliquer les filtres sur la relation invoice
            $query = $order->invoice()->orderBy('created_at', $data['order']);

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
                    // Extraire l'année et le numéro de la semaine
                    list($year, $week) = explode('-W', $data['week']);

                    // Convertir en entiers
                    $year = (int)$year;
                    $week = (int)$week;

                    // Récupérer les dates de début (lundi) et de fin (dimanche) de la semaine
                    $startOfWeek = (new DateTime())->setISODate($year, $week, 1); // Lundi
                    $endOfWeek = (new DateTime())->setISODate($year, $week, 7);  // Dimanche

                    $query->whereBetween('created_at', [
                        $startOfWeek->format('Y-m-d'),
                        $endOfWeek->format('Y-m-d'),
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

        $stores = $warehouse->stores;

        return view('pages.warehouse.invoice.list_order', compact('invoices', 'stores'));
    }

    public function filterInvoiceSupply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier' => 'nullable|string|exists:suppliers,supplier_name',
            'order' => 'required|in:desc,asc',
            'status' => 'required|in:all,settled,not-settled',
            'priority_level' => 'required|in:all,low,medium,high',
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
            'status.required' => __('messages.validate.status_required'),
            'status.in' => __('messages.validate.status_in'),
            'priority_level.required' => __('messages.validate.priority_level_required'),
            'priority_level.in' => __('messages.validate.priority_level_in'),
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

        $validator->after(function($validator) use ($request) {
            if ($request->order !== 'asc' && $request->order !== 'desc') {
                $validator->errors()->add('order', __('messages.validate.order_in'));
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

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
        $data = array_filter($request->only(['supplier', 'order', 'status', 'priority_level', 'type_date', 'day', 'week', 'month', 'year']), function ($value) {
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

            // Filtrer par niveau de priorité
            switch ($data['priority_level']) {
                case 'low':
                    // Factures non réglées depuis moins d'une semaine
                    $query->where('invoice_status', Invoice::INVOICE_STATUS_UNPAID);
                    $query->where('created_at', '>=', Carbon::now()->subWeek()); // Moins de 1 semaine
                    break;
                case 'medium':
                    // Factures non réglées depuis plus d'une semaine mais moins de deux semaines
                    $query->where('invoice_status', Invoice::INVOICE_STATUS_UNPAID);
                    $query->where('created_at', '>=', Carbon::now()->subWeeks(2)) // Plus d'une semaine
                          ->where('created_at', '<', Carbon::now()->subWeek()); // Moins de 2 semaines
                    break;
                case 'high':
                    // Factures non réglées depuis plus de deux semaines
                    $query->where('invoice_status', Invoice::INVOICE_STATUS_UNPAID);
                    $query->where('created_at', '<', Carbon::now()->subWeeks(2)); // Plus de 2 semaines
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
                    // Extraire l'année et le numéro de la semaine
                    list($year, $week) = explode('-W', $data['week']);

                    // Convertir en entiers
                    $year = (int)$year;
                    $week = (int)$week;

                    // Récupérer les dates de début (lundi) et de fin (dimanche) de la semaine
                    $startOfWeek = (new DateTime())->setISODate($year, $week, 1); // Lundi
                    $endOfWeek = (new DateTime())->setISODate($year, $week, 7);  // Dimanche

                    $query->whereBetween('created_at', [
                        $startOfWeek->format('Y-m-d'),
                        $endOfWeek->format('Y-m-d'),
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

        return view('pages.warehouse.invoice.list_supply', compact('invoices', 'suppliers'));
    }

    public function infoInvoiceSupply(string $invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->first();

        if (!$invoice) {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        $supply = $invoice->supply;

        // Vérifier si la facture est une facture de commande ou de fourniture
        if (!$supply) 
        {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        $total_amount = $supply->supplyLines->sum(fn($supply_line) => $supply_line->unit_price * $supply_line->quantity_supplied);

        return view('pages.warehouse.invoice.info_supply', compact('invoice', 'supply', 'total_amount'));
    }

    public function settleInvoice(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
        ], [
            'invoice_id.required' => __('messages.validate.invoice_not_found'),
            'invoice_id.integer' => __('messages.validate.invoice_not_found'),
            'invoice_id.exists' => __('messages.validate.invoice_not_found'),
        ]);

        $invoice_id = $request->input('invoice_id');

        $invoice = Invoice::find($invoice_id);

        // Vérifier si la facture n'est pas déjà réglée
        if ($invoice->invoice_status === Invoice::INVOICE_STATUS_PAID) {
            return redirect()->route('warehouse.invoice.list.supply')->with('error', __('messages.invoice_already_settled'));
        }

        // Vérifier si la facture n'est pas une commande
        if ($invoice->order)
        {
            return redirect()->route('warehouse.invoice.list.supply')->with('error', __('messages.invoice_not_found'));
        }

        // Mettre à jour le statut de la facture
        $success = $invoice->update([
            'invoice_status' => Invoice::INVOICE_STATUS_PAID,
        ]);

        if ($success){
            return redirect()->route('warehouse.invoice.info.supply', ['invoice_number' => $invoice->invoice_number])->with('success', __('messages.invoice_settled'));
        }
        else {
            return redirect()->route('warehouse.invoice.info.supply', ['invoice_number' => $invoice->invoice_number])->with('error', __('messages.invoice_not_settled'));
        }
    }

    public function showInvoice(string $invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->first();

        if (!$invoice) {
            return redirect()->route('warehouse.invoice.list.supply')->with('error', __('messages.invoice_not_found'));
        }

        $supply = $invoice->supply;

        if (!$supply) 
        {
            return redirect()->route('warehouse.invoice.list.supply')->with('error', __('messages.invoice_not_found'));
        }

        $total_amount = $supply->supplyLines->sum(fn($supply_line) => $supply_line->unit_price * $supply_line->quantity_supplied);

        $warehouse_name = $supply->warehouse->warehouse_name;

        $pdf = Pdf::loadView('pages.warehouse.invoice.supplier_pdf', compact('invoice', 'supply', 'total_amount', 'warehouse_name'));

        // Pour afficher le PDF dans le navigateur
        return $pdf->stream(str_replace(' ', '_', $warehouse_name).'_INVOICE_'.$invoice->invoice_number.'_'.str_replace(' ', '_', $invoice->created_at).'.pdf');
    }

    public function downloadInvoice(string $invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->first();

        if (!$invoice) {
            return redirect()->route('warehouse.invoice.list.supply')->with('error', __('messages.invoice_not_found'));
        }

        $supply = $invoice->supply;

        if (!$supply) 
        {
            return redirect()->route('warehouse.invoice.list.supply')->with('error', __('messages.invoice_not_found'));
        }

        $total_amount = $supply->supplyLines->sum(fn($supply_line) => $supply_line->unit_price * $supply_line->quantity_supplied);

        $warehouse_name = $supply->warehouse->warehouse_name;

        $pdf = Pdf::loadView('pages.warehouse.invoice.supplier_pdf', compact('invoice', 'supply', 'total_amount', 'warehouse_name'));

        // Pour afficher le PDF dans le navigateur
        return $pdf->download(str_replace(' ', '_', $warehouse_name).'_INVOICE_'.$invoice->invoice_number.'_'.str_replace(' ', '_', $invoice->created_at).'.pdf');
    }

    // ------------------------------------------
    //                  STORE
    // ------------------------------------------

    public function invoiceListStore()
    {
        $user = auth()->user();

        $store = $user->storeUser->store;

        // Récupère toutes les factures de l'entrepôt
        $invoices = $store->orders->flatMap(function ($order) {
            return $order->invoice ? [$order->invoice] : [];
        });

        $invoices = $invoices->sortByDesc('created_at');

        return view('pages.store.invoice.list', compact('invoices'));
    }

    public function filterInvoiceStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|in:desc,asc',
            'status' => 'required|in:all,settled,not-settled',
            'type_date' => 'required|in:all,day,week,month,year',
            'day' => 'nullable|date|before_or_equal:today',
            'week' => 'nullable|regex:/^\d{4}-W\d{2}$/|before_or_equal:today',
            'month' => 'nullable|date_format:Y-m|before_or_equal:today',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ], [
            'order.required' => __('messages.validate.order_required'),
            'order.in' => __('messages.validate.order_in'),
            'status.required' => __('messages.validate.status_required'),
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

        $validator->after(function($validator) use ($request) {
            if ($request->order !== 'asc' && $request->order !== 'desc') {
                $validator->errors()->add('order', __('messages.validate.order_in'));
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

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
        $data = array_filter($request->only(['store', 'order', 'status', 'priority_level', 'type_date', 'day', 'week', 'month', 'year']), function ($value) {
            return $value !== null && $value !== '';
        });

        $user = auth()->user();

        $store = $user->storeUser->store;

        $orders = $store->orders;

        // Récupère les factures en fonction des critères
        $invoices = $orders->flatMap(function ($order) use ($data) {
            // Appliquer les filtres sur la relation invoice
            $query = $order->invoice()->orderBy('created_at', $data['order']);

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
                    // Extraire l'année et le numéro de la semaine
                    list($year, $week) = explode('-W', $data['week']);

                    // Convertir en entiers
                    $year = (int)$year;
                    $week = (int)$week;

                    // Récupérer les dates de début (lundi) et de fin (dimanche) de la semaine
                    $startOfWeek = (new DateTime())->setISODate($year, $week, 1); // Lundi
                    $endOfWeek = (new DateTime())->setISODate($year, $week, 7);  // Dimanche

                    $query->whereBetween('created_at', [
                        $startOfWeek->format('Y-m-d'),
                        $endOfWeek->format('Y-m-d'),
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

        return view('pages.store.invoice.list', compact('invoices'));
    }

    public function searchInvoiceStore(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ], [
            'search.required' => __('messages.validate.search_required'),
            'search.string' => __('messages.validate.search_string'),
        ]);

        $invoice = Invoice::where('invoice_number', $request->input('search'))->first();

        if (!$invoice) {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        // Vérifier si la facture est une facture de commande ou de fourniture
        if ($invoice->order) {
            return redirect()->route('store.invoice.info', ['invoice_number' => $invoice->invoice_number]);
        }
        else {
            return redirect()->route('store.invoice.list')->with('error', __('messages.invoice_not_found'));
        }
    }

    public function infoInvoiceStore(string $invoice_number)
    {
        $invoice = Invoice::where('invoice_number', $invoice_number)->first();

        if (!$invoice) {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        $order = $invoice->order;

        if (!$order) 
        {
            return redirect()->back()->with('error', __('messages.invoice_not_found'));
        }

        $warehouse = $order->store->warehouse;
        
        $total_amount_ht = $order->calculateTotalPrice();
        $total_amount_ttc = $total_amount_ht * $warehouse->global_margin;

        return view('pages.store.invoice.info', compact('invoice', 'order', 'warehouse', 'total_amount_ht', 'total_amount_ttc'));
    } 

    public function settleInvoiceStore(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
        ], [
            'invoice_id.required' => __('messages.validate.invoice_not_found'),
            'invoice_id.integer' => __('messages.validate.invoice_not_found'),
            'invoice_id.exists' => __('messages.validate.invoice_not_found'),
        ]);

        $invoice_id = $request->input('invoice_id');

        $invoice = Invoice::find($invoice_id);

        // Vérifier si la facture n'est pas déjà réglée
        if ($invoice->invoice_status === Invoice::INVOICE_STATUS_PAID) {
            return redirect()->route('store.invoice.list')->with('error', __('messages.invoice_already_settled'));
        }

        // Vérifier si la facture n'est pas un approvisionnement
        if ($invoice->supply)
        {
            return redirect()->route('store.invoice.list')->with('error', __('messages.invoice_not_found'));
        }

        // Mettre à jour le statut de la facture
        $success = $invoice->update([
            'invoice_status' => Invoice::INVOICE_STATUS_PAID,
        ]);

        if ($success){
            return redirect()->route('store.invoice.info', ['invoice_number' => $invoice->invoice_number])->with('success', __('messages.invoice_settled'));
        }
        else {
            return redirect()->route('store.invoice.info', ['invoice_number' => $invoice->invoice_number])->with('error', __('messages.invoice_not_settled'));
        }
    }
}
