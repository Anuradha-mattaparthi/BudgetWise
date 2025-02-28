<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>BudgetWise</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />


        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/css/style.css','resources/js/app.js'])
    </head>
    <body class="homepagebgcolor">
        <header class="customnav">

            @if (Route::has('login'))
                <livewire:welcome.navigation />
            @endif
        </header>
         <main class="customhompage">
         </main>

    </body>
    <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 24 150 28"
    preserveAspectRatio="none"
  >
    <defs>
      <path
        id="gentle-wave"
        d="M-160 44c30 0
      58-18 88-18s
      58 18 88 18
      58-18 88-18
      58 18 88 18
      v44h-352z"
      />
    </defs>
    <g class="waves">
      <use
        xlink:href="#gentle-wave"
        x="50"
        y="0"
        fill="#f0f0f0"
        fill-opacity=".2"
      />
      <use
        xlink:href="#gentle-wave"
        x="50"
        y="3"
        fill="#f0f0f0"
        fill-opacity=".5"
      />
      <use
        xlink:href="#gentle-wave"
        x="50"
        y="6"
        fill="#f0f0f0"
        fill-opacity=".9"
      />
    </g>
  </svg>
</html>
