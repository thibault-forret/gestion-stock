@extends('layouts.app')

@section('css')
    <style>
        .content {
            height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
            padding: 20px;
            animation: fadeIn 1s ease-out;
        }

        .container h1 {
            font-size: 10rem;
            font-weight: bold;
            margin: 0;
            color: #ff6f61;
            animation: bounce 5s infinite ease-in-out;
            user-select: none;
        }

        .container p {
            font-size: 1.5rem;
            margin: 20px 0;
            color: #555;
            user-select: none;
        }

        .container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            font-size: 1rem;
            color: #fff;
            background-color: #ff6f61;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
        }

        .container a:hover {
            transform: translateY(-3px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
@endsection

@section('title', 'Page non trouvée')

@section('content')
    <div class="container text-center">
        <h1>404</h1>
        <p>{{ __('basics.page_not_exist') }}</p>
        <a href="{{ url('/') }}" class="btn btn-primary">{{ __('basics.back_home') }}</a>
    </div>
@endsection
