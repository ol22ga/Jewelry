@extends('layout.app')
@section('title')
    Каталог
@endsection
@section('content')
    <div id="ProductsFunctions">
        <!-- Основное контент -->
        <div class="container">
            <div class="row  mt-5  col-12  text-center"><h2>Каталог</h2></div>
            <div class="row  mt-5">
                <div class="mb-5  col-12  col-md-4  col-xl-3">
                    <label for="shop_id">Выбрать филиал</label>
                    <div class="col-12  mb-2">
                        <select class="form-select" name="" id="shop_id" v-model="shop_id">
                            <option value="0">Все филиалы</option>
                            <option v-for="shop in shops" :value="shop.id"> @{{ shop.title }} </option>
                        </select>
                    </div>
                    <label for="sorted">Сортировать</label>
                    <div class="col-12  mb-2">
                        <select class="form-select" name="" id="sorted" v-model="sorted">
                            <option value="created_at">Сначала новые</option>
                            <option value="cheap">Сначала дешевые</option>
                            <option value="expensive">Сначала дорогие</option>
                            <option value="title">По названию</option>
                        </select>
                    </div>
                    <label for="">Выбрать фильтры</label>
                    <div class="col-12  mb-2">
                        <select class="form-select" name="" id="" v-model="type_id">
                            <option value="0">Все типы</option>
                            <option v-for="type in types" :value="type.id"> @{{ type.title }} </option>
                        </select>
                    </div>
                    <div class="col-12  mb-2">
                        <select class="form-select" name="" id="" v-model="brand_id">
                            <option value="0">Все бренды</option>
                            <option v-for="brand in brands" :value="brand.id"> @{{ brand.title }} </option>
                        </select>
                    </div>
                    <div class="col-12  mb-2">
                        <select class="form-select" name="" id="" v-model="metal_id">
                            <option value="0">Все металлы</option>
                            <option v-for="metal in metals" :value="metal.id"> @{{ metal.title }} </option>
                        </select>
                    </div>
                    <div class="col-12  mb-2">
                        <select class="form-select" name="" id="" v-model="jewel_id">
                            <option value="0">Все драгоценности</option>
                            <option v-for="jewel in jewels" :value="jewel.id"> @{{ jewel.title }} </option>
                        </select>
                    </div>
                    <div class="col-12" style="display: flex; align-items: center; justify-content: space-between; flex-direction: row">
                        <input type="number" class="form-control" placeholder="от" style="width: 47%" id='price_min' v-model="price_min">
                        <input type="number" class="form-control" placeholder="до" style="width: 47%" id='price_max' v-model="price_max">
                    </div>
                </div>
                <div class="col-12  col-md-8  col-xl-9">
                    <div v-if="FilterProducts.length > 0">
                        <div class="row">
                            <div v-for="product in FilterProducts" class="col-12  col-sm-6  col-md-6  col-xl-4">
                                <div class="card  product-card" @click="RenderModalShow(product.id)" data-bs-toggle="modal" data-bs-target="#ShowModal" style="margin-bottom: 24px;">
                                    <div style="height: 290px; overflow: hidden">
                                        <img :src="product.img" class="card-img-top  product-card__img" :alt="product.title" style="height: 100%; object-fit: cover">
                                    </div>
                                    <div class="card-body" style="display: flex; align-items: flex-start; justify-content: space-between; flex-direction: column">
                                        <h5 class="card-title" style="width: 100%; min-height: 48px; margin-bottom: 8px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-align: start"> @{{product.title}} </h5>
                                        <div>
                                            <p class="card-text" style="margin-bottom: 8px; min-height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-align: justify">Металл: @{{product.category_metal.title}};     Вставка: @{{product.category_jewel.title}} </p>
                                        </div>
                                        @auth()
                                        <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; flex-direction: row">
                                            <p class="card-text" style="font-weight: bold; margin: 0; margin-right: 20px;"> @{{product.price}} ₽ </p>
                                            <p v-if="product.product_shops_sum_count == 0 || product.product_shops_sum_count == null" class="card-text">Нет в наличии</p>
                                            <button v-else type="submit" class="btn  btn-primary" style="width: 50%; z-index: 100"  @click="AddToCart(product.id)">В корзину</button>
                                        </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p v-else style="font-size: 24px">К сожалению, подходящих товаров не найдено</p>
                </div>
            </div>
        </div>

        <!-- Модальное окно просмотра -->
        <div class="modal  fade" id="ShowModal" tabindex="-1" aria-labelledby="ShowModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ShowModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body" id="ShowModalBody" style="display: flex; flex-direction: column; justify-content: start; align-items: center">
                            <div class="col-12" :class="message ? 'mb-3  alert  alert-secondary  text-center' : ''">@{{ message }}</div>
                        <div style="display: flex; flex-direction: row; align-items: start; justify-content: space-between; width: 100%;">
                            <div id="ShowModalImg" style="width: 50%"></div>
                            <div style="width: 50%">
                                <p style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #dee2e6"><span>Бренд</span><span id="ShowModalBrand"></span></p>
                                <p style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #dee2e6; margin-top: 10px"><span>Тип изделия</span><span id="ShowModalType"></span></p>
                                <p style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #dee2e6; margin-top: 10px"><span>Пол</span><span id="ShowModalGender"></span></p>
                                <p style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #dee2e6; margin-top: 10px"><span>Металл</span><span id="ShowModalMetal"></span></p>
                                <p style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #dee2e6; margin-top: 10px"><span>Драгоценность</span><span id="ShowModalJewel"></span></p>
                                <p style="display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 1px solid #dee2e6; margin-top: 10px"><span>Вес</span><span id="ShowModalWeight"></span></p>
                            </div>
                        </div>
                        <div style="width: 100%;">
                            <p style="font-size: 18px; font-weight: bold">Описание</p>
                            <p id="ShowModalDescription"></p>
                            <p style="display: flex; align-items: flex-start; justify-content: start; margin-top: 10px; font-weight: bold; font-size: 18px">Цена: &nbsp;<span id="ShowModalPrice" style=""></span></p>
                            <p id="ShowModalAvailable" class="card-text" style=" width: 100%; height: 38.48px; display: flex; align-items: center; justify-content: start"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .product-card {
            cursor: pointer;
        }
        .product-card__img {
            transition: .3s;
        }
        .product-card:hover .product-card__img {
            transform: scale(1.03);
        }
    </style>

    <script>
        function equalHeight(group) {
            var tallest = 0;
            group.each(function() {
                thisHeight = $(this).height();
                if(thisHeight > tallest) {
                    tallest = thisHeight;
                }
            });
            group.height(tallest);
        }
        $(document).ready(function(){
            equalHeight($(".card"));
        });
    </script>

    <script>
        const ProductsFunctions = {
            data() {
                return {
                    products: [],
                    products_with_shops: [],
                    brands: [],
                    types: [],
                    metals: [],
                    jewels: [],
                    genders: [],
                    shops: [],
                    products_and_shops: [],

                    shop_id: 0,
                    brand_id: 0,
                    type_id: 0,
                    metal_id: 0,
                    jewel_id: 0,

                    sorted: 'created_at',

                    price_min: 0,
                    price_max: 0,

                    message: ''
                }
            },
            methods: {
                //---Получение товаров
                async GetProducts() {
                    const response = await fetch('{{route('ProductGet')}}');
                    const data = await response.json();
                    this.products = data.all;
                    this.products_with_shops = data.products_with_shops;
                    this.GetMaxAndMinPrice();
                },
                //---Получение брендов
                async GetBrands() {
                    const response = await fetch('{{route('BrandGet')}}');
                    const data = await response.json();
                    this.brands = data.all;
                },
                //---Получение типов
                async GetTypes() {
                    const response = await fetch('{{route('TypeGet')}}');
                    const data = await response.json();
                    this.types = data.all;
                },
                //---Получение металлов
                async GetMetals() {
                    const response = await fetch('{{route('MetalGet')}}');
                    const data = await response.json();
                    this.metals = data.all;
                },
                //---Получение драгоценностей
                async GetJewels() {
                    const response = await fetch('{{route('JewelGet')}}');
                    const data = await response.json();
                    this.jewels = data.all;
                },
                //---Получение пола
                async GetGenders() {
                    const response = await fetch('{{route('GenderGet')}}');
                    const data = await response.json();
                    this.genders = data.all;
                },
                //---Получение магазинов
                async GetShops() {
                    const response = await fetch('{{route('ShopGet')}}');
                    const data = await response.json();
                    this.shops = data.all;
                },
                //---Получение таблицы "Магазин-Товар"
                async GetProductsAndShops() {
                    const response = await fetch('{{route('GetProductsAndShops')}}');
                    const data = await response.json();
                    this.products_and_shops = data.all;
                },
                //---Отрисовка модального окна просмотра
                RenderModalShow(id) {
                    var title = this.products.find(product => product.id === id).title;
                    document.getElementById('ShowModalLabel').innerText = title;
                    var img = this.products.find(product => product.id === id).img;
                    document.getElementById('ShowModalImg').innerHTML = "<img src='" + img + "' alt='" + title + "' style='width: 100%; height: auto; object-fit: cover'>";
                    document.getElementById('ShowModalBrand').innerText = this.brands.find(brand => brand.id === (this.products.find(product => product.id === id).brand_id)).title;
                    document.getElementById('ShowModalPrice').innerText = this.products.find(product => product.id === id).price + ' ₽';
                    document.getElementById('ShowModalDescription').innerText = this.products.find(product => product.id === id).description;
                    document.getElementById('ShowModalType').innerText = this.types.find(type => type.id === (this.products.find(product => product.id === id).category_type_id)).title;
                    document.getElementById('ShowModalGender').innerText = this.genders.find(gender => gender.id === (this.products.find(product => product.id === id).category_gender_id)).title;
                    document.getElementById('ShowModalMetal').innerText = this.metals.find(metal => metal.id === (this.products.find(product => product.id === id).category_metal_id)).title;
                    document.getElementById('ShowModalJewel').innerText = this.jewels.find(jewel => jewel.id === (this.products.find(product => product.id === id).category_jewel_id)).title;
                    document.getElementById('ShowModalWeight').innerText = this.products.find(product => product.id === id).weight + " г.";

                    if ((this.products.find(product => product.id === id).product_shops_sum_count) == 0 || (this.products.find(product => product.id === id).product_shops_sum_count) == null) {
                        document.getElementById('ShowModalAvailable').innerText = 'Нет в наличии';
                        document.getElementById('ShowModalAvailable').style.justifyContent = 'center';
                        document.getElementById('ShowModalButton').style.display = 'none';
                    } else {
                        var count_shops = this.products.find(product => product.id === id).product_shops_count;
                        var count = this.products.find(product => product.id === id).product_shops_sum_count;

                        if (count_shops % 10 == 1) {
                            document.getElementById('ShowModalAvailable').innerText = 'В наличии в ' + count_shops + ' магазине (' + count + ' шт)';
                        } else {
                            document.getElementById('ShowModalAvailable').innerText = 'В наличии в ' + count_shops + ' магазинах (' + count + ' шт)';
                        }

                        document.getElementById('ShowModalAvailable').style.justifyContent = 'start';
                        document.getElementById('ShowModalButton').style.display = 'block';
                    }
                },
                //---Добавление товара в корзину
                async AddToCart(id) {
                    const response = await fetch('{{route('CartAdd')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id:id
                        })
                    });
                    if (response.status === 200) {
                        this.message = await response.json();
                        setTimeout(()=>{this.message = null}, 20000);
                    }
                    if (response.status === 400) {
                        this.message = await response.json();
                        setTimeout(()=>{this.message = null}, 20000);
                    }
                    if (response.status === 500) {
                        this.AddToCart(id);
                    }
                },
                //---Получение максимальной и минимальной цены
                GetMaxAndMinPrice() {
                    let reserve = [];
                    for (product of this.products) {
                        reserve.push(Number(product.price));
                    }
                    this.price_max = Math.max.apply(null, reserve);
                    this.price_min = Math.min.apply(null, reserve);
                    reserve = [];
                }
            },
            computed: {
                FilterProducts() {
                    if (this.shop_id == 0) {
                        var products_arr = this.products;
                    } else {
                        var products_arr = this.products_with_shops
                            .filter(product => {
                                return this.shop_id == 0 || product.shop_id == this.shop_id;
                            })
                    }

                    return products_arr
                        .filter(product => {
                            return this.brand_id == 0 || product.brand_id == this.brand_id;
                        })
                        .filter(product => {
                            return this.type_id == 0 || product.category_type_id == this.type_id;
                        })
                        .filter(product => {
                            return this.metal_id == 0 || product.category_metal_id == this.metal_id;
                        })
                        .filter(product => {
                            return this.jewel_id == 0 || product.category_jewel_id == this.jewel_id;
                        })
                        .filter(product => {
                            return Number(product.price) >= this.price_min && Number(product.price) <= this.price_max;
                        })
                        .sort((a, b) => {
                            if (this.sorted === 'expensive') {
                                if (a['price'] > b['price']) return -1
                                if (a['price'] < b['price']) return 1
                                if (a['price'] = b['price']) return 0
                            }
                            if (this.sorted === 'cheap') {
                                if (a['price'] > b['price']) return 1
                                if (a['price'] < b['price']) return -1
                                if (a['price'] = b['price']) return 0
                            }
                            if (this.sorted === 'title') {
                                if (a['title'] > b['title']) return 1
                                if (a['title'] < b['title']) return -1
                                if (a['title'] = b['title']) return 0
                            }
                        })
                }
            },
            mounted() {
                if (sessionStorage.getItem('type_id') !== null) {
                    this.type_id = sessionStorage.getItem('type_id');
                }

                if (sessionStorage.getItem('shop_id') !== null) {
                    this.shop_id = sessionStorage.getItem('shop_id');
                }

                this.GetProductsAndShops();
                this.GetProducts();
                this.GetBrands();
                this.GetTypes();
                this.GetMetals();
                this.GetJewels();
                this.GetGenders();
                this.GetShops();
                sessionStorage.removeItem('type_id');
                sessionStorage.removeItem('shop_id');
            }
        }
        Vue.createApp(ProductsFunctions).mount('#ProductsFunctions');
    </script>
@endsection

