@extends('layouts.app')

@section('css')
    <link href="{{ mix('css/pages/dashboard.css') }}" rel="stylesheet">
@endsection

@section('title', __('title.dashboard'))
@section('description', __('description.dashboard.store'))
@section('title-content', mb_strtoupper(__('title.dashboard'), 'UTF-8'))

@section('content')
    <div class="content">
        <div class="container">
            <div class="icon-construct">
                <i class="fas fa-door-open"></i>
            </div>
            <h1>{{ __('dashboard.welcome') }}</h1>
            <p>{{ __('dashboard.welcome_text') }}</p>
            <div class="buttons">
                <a href="{{ route('store.order.index') }}" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                        <path d="M9,22c0,1.105-.895,2-2,2s-2-.895-2-2,.895-2,2-2,2,.895,2,2Zm8-2c-1.105,0-2,.895-2,2s.895,2,2,2,2-.895,2-2-.895-2-2-2ZM5.419,13l-.941-8h5.591c.087-.699,.262-1.369,.518-2H4.242l-.041-.351c-.178-1.511-1.459-2.649-2.979-2.649H0V2H1.222c.507,0,.934,.38,.993,.884l1.584,13.467c.178,1.511,1.459,2.649,2.979,2.649h13.222v-2H6.778c-.507,0-.934-.38-.993-.884l-.131-1.116H21.835l.363-2H5.419ZM24,6c0,3.309-2.691,6-6,6s-6-2.691-6-6S14.691,0,18,0s6,2.691,6,6Zm-2,0c0-2.206-1.794-4-4-4s-4,1.794-4,4,1.794,4,4,4,4-1.794,4-4Zm-3-3h-2v3.414l2.293,2.293,1.414-1.414-1.707-1.707V3Z"/>
                      </svg>
                    <div class="text">{{ __('title.order') }}</div>
                </a>

                <a href="{{ route('store.invoice.list') }}" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="icon">
                        <path d="M24,20c0,1.654-1.346,3-3,3v1h-2v-1c-1.654,0-3-1.346-3-3h2c0,.551,.448,1,1,1h2c.552,0,1-.449,1-1,0-.378-.271-.698-.644-.76l-3.041-.507c-1.342-.223-2.315-1.373-2.315-2.733,0-1.654,1.346-3,3-3v-1h2v1c1.654,0,3,1.346,3,3h-2c0-.551-.448-1-1-1h-2c-.552,0-1,.449-1,1,0,.378,.271,.698,.644,.76l3.041,.507c1.342,.223,2.315,1.373,2.315,2.733Zm-9.899-5c.152-.743,.482-1.416,.924-2H5v7H14v-2H7v-3h7.101ZM5,11h5v-2H5v2Zm5-6H5v2h5v-2Zm6.031,19H1V3C1,1.346,2.346,0,4,0H13.414l7.586,7.586v2.414h-2v-1h-7V2H4c-.551,0-1,.449-1,1V22H14.424c.352,.801,.913,1.483,1.607,2ZM14,7h3.586l-3.586-3.586v3.586Z"/>
                    </svg>
                    <div class="text">{{ __('title.invoice') }}</div>
                </a>
            </div>
        </div>
    </div>
@endsection
