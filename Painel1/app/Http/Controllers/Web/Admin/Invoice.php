<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Controller;
use App\Models\Invoice as ModelsInvoice;
use App\Models\Product;
use App\Models\Server;
use App\Models\User;

/**
 * Invoice class
 */
class Invoice extends Controller
{
    /**
     * Get the sales data for the dashboard
     *
     * @return The view is being returned.
     */
    public function dashboard()
    {
        //get sales data
        $sales = [
          'pending' => ModelsInvoice::where('state', 'pending')->sum('value'),
          'approved' => ModelsInvoice::where('state', 'approved')->sum('value'),
          'total' => ModelsInvoice::whereIn('state', ['approved', 'completed'])->sum('value')
        ];

        //format sales values
        $sales = array_map(
            function ($number) {
                return number_format($number, 2, ',', '');
            },
            $sales
        );

        //get top user consume
        $topInvoice = ModelsInvoice::where('state', 'approved')->orderBy('value', 'desc')->first();
        $top_recharge = [
          'invoice' => $topInvoice?->toArray(),
          'total' => ModelsInvoice::where('state', 'approved')->where('uid', $topInvoice?->uid)->sum('value'),
          'user' => User::find($topInvoice?->uid)
        ];

        //get last invoice detail
        $lastInvoices = ModelsInvoice::select()->orderBy('created_at', 'desc')->limit(6)->get()?->toArray();
        $lastInvoices = array_map(function ($invoice) {
            $invoice['user'] = User::find($invoice['uid']);
            $invoice['product'] = Product::find($invoice['pid']);
            return $invoice;
        }, $lastInvoices);

        $sales['count'] = ModelsInvoice::invoicesMonth()->count();
        $sales['countPercent'] = ($sales['count'] > 0) ? round(($sales['count'] * 100) / 100, 2) : 0 ;
        return $this->view->render('admin.invoice.dashboard', [
          'sales' => $sales,
          'top_recharge' => $top_recharge,
          'earningsMonth' => ModelsInvoice::earningsMonth(),
          'lastInvoices' => $lastInvoices
        ]);
    }

    /**
     * This function returns the view of the list of invoices
     *
     * @return The view object.
     */
    public function list()
    {
        return $this->view->render('admin.invoice.list', [
            'servers' => Server::all(),
        ]);
    }

    /**
     * This function is called when the user clicks the create invoice button
     *
     * @return The view is being returned.
     */
    public function create()
    {
        return $this->view->render('admin.invoice.create', [
          'servers' => Server::all()
        ]);
    }
}
