@if ($icon === 'custom-icon')
    <svg id="Ribs--Streamline-Atlas" xmlns="http://www.w3.org/2000/svg" viewBox="-0.5 -0.5 16 16" height="16"
        width="16">
        <desc>Ribs Streamline Icon: https://streamlinehq.com</desc>
        <defs></defs>
        <path
            d="M11.693750000000001 2.1125h-1.2a1.1875 1.1875 0 0 0 -1.1937499999999999 1.1937499999999999A1.1937499999999999 1.1937499999999999 0 0 0 8.125 2.1125h-1.25a1.1937499999999999 1.1937499999999999 0 0 0 -1.1749999999999998 1.1937499999999999 1.1875 1.1875 0 0 0 -1.1937499999999999 -1.1937499999999999H3.30625a1.1875 1.1875 0 0 0 -1.1937499999999999 1.1937499999999999v8.3875a1.1875 1.1875 0 0 0 1.1937499999999999 1.1937499999999999h1.2a1.1875 1.1875 0 0 0 1.1937499999999999 -1.1937499999999999A1.1937499999999999 1.1937499999999999 0 0 0 6.875 12.887500000000001h1.25a1.1937499999999999 1.1937499999999999 0 0 0 1.2 -1.1937499999999999 1.1875 1.1875 0 0 0 1.1937499999999999 1.1937499999999999h1.2a1.1875 1.1875 0 0 0 1.1937499999999999 -1.1937499999999999V3.30625a1.1875 1.1875 0 0 0 -1.21875 -1.1937499999999999Z"
            fill="none" stroke="#000000" stroke-miterlimit="10" stroke-width="1"></path>
        <path d="m3.90625 0.3125 0 1.7999999999999998" fill="none" stroke="#000000" stroke-miterlimit="10"
            stroke-width="1"></path>
        <path d="m7.5 0.3125 0 1.7999999999999998" fill="none" stroke="#000000" stroke-miterlimit="10"
            stroke-width="1"></path>
        <path d="m11.09375 0.3125 0 1.7999999999999998" fill="none" stroke="#000000" stroke-miterlimit="10"
            stroke-width="1"></path>
        <path d="m3.90625 12.893749999999999 0 1.7937500000000002" fill="none" stroke="#000000"
            stroke-miterlimit="10" stroke-width="1"></path>
        <path d="m7.5 12.893749999999999 0 1.7937500000000002" fill="none" stroke="#000000" stroke-miterlimit="10"
            stroke-width="1"></path>
        <path d="m11.09375 12.893749999999999 0 1.7937500000000002" fill="none" stroke="#000000"
            stroke-miterlimit="10" stroke-width="1"></path>
        <path d="m5.706250000000001 8.100000000000001 0 3.59375" fill="none" stroke="#000000" stroke-miterlimit="10"
            stroke-width="1"></path>
        <path d="m9.3 3.30625 0 2.4" fill="none" stroke="#000000" stroke-miterlimit="10" stroke-width="1"></path>
        <path d="m9.3 6.8999999999999995 0 2.99375" fill="none" stroke="#000000" stroke-miterlimit="10"
            stroke-width="1"></path>
        <path d="m5.706250000000001 4.50625 0 2.39375" fill="none" stroke="#000000" stroke-miterlimit="10"
            stroke-width="1"></path>
        <path d="m9.3 11.09375 0 0.6" fill="none" stroke="#000000" stroke-miterlimit="10" stroke-width="1"></path>
    </svg>
@else
    {{ $getIconHtml() }}
@endif
