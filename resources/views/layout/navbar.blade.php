<div id="Navbar">
{{--    Навбар--}}
    <nav class="navbar navbar-expand-lg  bg-body-tertiary  navbar-light">
        <div class="container-fluid  container">
            <a class="navbar-brand" href="{{route('MainPage')}}">Jewelry</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('MainPage')}}">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('CatalogPage')}}">Каталог</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('ShopsPage')}}">Магазины</a>
                    </li>
                    @auth()
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('CartPage')}}">Корзина</a>
                    </li>
                    @endauth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" style="fill: #0d6efd;" width="16" height="16"
                                     fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd"
                                          d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                            </a>
                            <ul class="dropdown-menu">
                                @guest()
                                <li><a class="dropdown-item" href="{{route('RegistrationPage')}}">Регистрация</a></li>
                                <li><a class="dropdown-item" href="{{route('login')}}">Авторизация</a></li>
                                @endguest
                                @auth()
                                <li><a class="dropdown-item" href="{{route('MyOrderPage')}}">Мои заказы</a></li>
                                <li><a class="dropdown-item" href="{{route('Exit')}}">Выход</a></li>
                                    @endauth
                            </ul>
                        </li>
                </ul>
                <form class="d-flex" @submit.prevent="ShowResults()">
                    <input class="form-control me-2" type="text" placeholder="Введите текст запроса" id="search" name="search">
                    <button class="btn btn-outline-primary" type="submit">Найти</button>
                </form>
            </div>
        </div>
    </nav>

    {{--    Админ-панель--}}
    @auth()
        @if(Auth::user() && Auth::user()->role === 'admin')
            <button class="float-end" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button" style="position: absolute; top: 100px; bottom: 0; left: -36px; background-color: #366afa; width: 72px; height: 36px; display: flex; align-items: center; justify-content: end; border-radius: 0.25rem; border: none">
                <iconify-icon icon="clarity:administrator-solid" style="color: white;" width="20" height="20"></iconify-icon>
            </button>

            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" data-bs-keyboard="false" data-bs-backdrop="false" style="width: 20% !important;">
                <div class="offcanvas-header" style="padding-left: 40px !important; padding-right: 40px !important; padding-top: 40px !important;">
                    <h6 class="offcanvas-title d-none d-sm-block" id="offcanvas">Админ-панель</h6>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body px-0" style="padding-left: 40px !important; padding-right: 40px !important;">
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start" id="menu">
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle  text-truncate" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding-left: 0 !important;">
                                <i class="fs-5 bi-bootstrap"></i><span class="ms-1 d-none d-sm-inline">Категории</span>
                            </a>
                            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdown">
                                <li><a class="dropdown-item" href="{{route('TypePage')}}">Тип изделия</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{route('GenderPage')}}">Пол</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{route('MetalPage')}}">Металл</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{route('JewelPage')}}">Драгоценность</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('BrandPage')}}" class="nav-link text-truncate" style="padding-left: 0 !important;">
                                <i class="fs-5 bi-house"></i><span class="ms-1 d-none d-sm-inline">Бренды</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('ShopPage')}}" class="nav-link text-truncate" style="padding-left: 0 !important;">
                                <i class="fs-5 bi-house"></i><span class="ms-1 d-none d-sm-inline">Магазины</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('ProductPage')}}" class="nav-link text-truncate" style="padding-left: 0 !important;">
                                <i class="fs-5 bi-house"></i><span class="ms-1 d-none d-sm-inline">Товары</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('OrderPage')}}" class="nav-link text-truncate" style="padding-left: 0 !important;">
                                <i class="fs-5 bi-house"></i><span class="ms-1 d-none d-sm-inline">Заказы</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    @endauth
</div>

<script>
    const NavFunctions = {
        data() {
            return {
                search: ''
            }
        },
        methods: {
            ShowResults() {
                sessionStorage.setItem('search', document.getElementById('search').value);
                if (sessionStorage.getItem('search') == '') {
                    this.RedirectCatalog();
                } else {
                    this.RedirectResults();
                }
            },
            async RedirectResults() {
                const response = await fetch('{{route('ResultsPage')}}');
                window.location = response.url;
            },
            async RedirectCatalog() {
                const response = await fetch('{{route('CatalogPage')}}');
                window.location = response.url;
            }
        },
        mounted() {
            var page = document.location.href;

            if (page == 'http://jewelry/results') {
                document.getElementById('search').value = sessionStorage.getItem('search');
            }
        }
    }
    Vue.createApp(NavFunctions).mount('#Navbar');
</script>
