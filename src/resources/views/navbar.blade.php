<header
    class="
        h-16
        border-b
        border-slate-200
        bg-white
    "
>

    <div
        class="
            flex
            h-full
            items-center
            justify-between
            px-6
        "
    >


        {{-- ============================================= --}}
        {{-- MARCA --}}
        {{-- ============================================= --}}

        <div>

            <a
                href="{{ route('dashboard') }}"
                class="
                    text-xl
                    font-bold
                    text-slate-900
                    no-underline
                "
            >

                {{ config(
                    'branding.site_name',
                    'AutoEscuela Virtual'
                ) }}

            </a>

        </div>



        {{-- ============================================= --}}
        {{-- USUARIO --}}
        {{-- ============================================= --}}

        <div
            class="
                flex
                items-center
                gap-4
            "
        >


            <div class="text-right">

                <p
                    class="
                        text-sm
                        font-semibold
                        text-slate-800
                    "
                >

                    {{ auth()->user()->name }}

                </p>


                <p
                    class="
                        text-xs
                        capitalize
                        text-slate-500
                    "
                >

                    {{ auth()->user()->role }}

                </p>

            </div>



            {{-- ============================================= --}}
            {{-- LOGOUT --}}
            {{-- ============================================= --}}

            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf


                <button
                    type="submit"
                    class="
                        rounded-lg
                        bg-slate-900
                        px-4
                        py-2
                        text-sm
                        font-medium
                        text-white
                        transition
                        hover:bg-slate-700
                    "
                >

                    Salir

                </button>

            </form>


        </div>


    </div>

</header>