<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoiceList()
    {
        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;

        // Récupère toutes les factures de l'entrepôt
        $invoices = $warehouse->supplies->map(function ($supply) {
            return $supply->invoice()->orderBy('created_at', 'desc')->get();
        });

        return view('pages.warehouse.invoice.list', compact('invoices'));
    }

    public function filterInvoice(Request $request)
    {
        // desc ou asc
        // status
        // date (jour, mois, année)
        $request->validate([
            'order' => 'required|in:desc,asc',
            'status' => '',
            'type_date' => 'required|in:all,day,week,month,year',
            'day' => 'nullable|',
            'week' => 'nullable|',
            'month' => 'nullable|',
            'year' => 'nullable|'
        ], [
            '' => ''
        ]);

        $user = auth()->user();

        $warehouse = $user->warehouseUser->warehouse;


        return view('pages.warehouse.invoice.list');
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
