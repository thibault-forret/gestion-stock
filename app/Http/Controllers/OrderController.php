<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function indexStore()
    {
        return view('pages.store.order.index');
    }

    public function placeOrderStore() 
    {
        // Ajouter des contenues au panier

        return view('pages.store.order.place_order');
    }

    public function storeDataInTheCartStore(Request $request)
    {
        // Stocker les données dans le panier

        return redirect()->route('store.order.recap');
    }

    public function recapOrderStore()
    {
        // Récupérer contenu du panier

        return view('pages.store.order.recap_order');
    }

    public function confirmOrderStore(Request $request)
    {
        // Vérification des données

        // Envoyer mail avec récapitulatif commande + facture ?

        return redirect()->route('store.order.index')->with('success', __('messages.confirm_order'));
    }

}

// Order process :

// Place order
//    -> Add content to cart
//    -> Confirm what he has added
//    -> Send to recap
// Recap order
//   -> Show what he has added
//   -> Confirm order
// Confirm order

// We need to store the cart in the database, maybe new database ?
// How to store data in the cart ?
