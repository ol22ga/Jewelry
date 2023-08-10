@extends('layout.app')
@section('title')
    Корзина
@endsection
@section('content')
    <div id="ProductsFunctions">
        <!-- Основное контент -->
        <div class="container">
            <div class="row  mt-5  col-12  text-center"><h2>Корзина</h2></div>
            <div class="row  justify-content-center">
                <div class="col-12" :class="message ? 'mt-5  alert  alert-secondary  text-center' : ''" style="margin-bottom: 0 !important;">@{{ message }}</div>
            </div>
            <div class="row  mt-5" v-if="carts.length > 0">
                <div class="col-8  col-sm-12  col-md-12  col-xl-9">
                    <div>
                        <div class="row">
                            <div v-for="cart in carts" class="col-12  col-sm-6  col-md-4  col-xl-4" style="position: relative">
                                <div class="card  product-card" style="margin-bottom: 24px;">
                                    <button type="submit" class="btn  btn-danger" @click="DeleteToCart(cart.id)" style="width: 40px; position: absolute; top: 10px; right: 10px; z-index: 10">X</button>
                                    <div style="height: 290px; overflow: hidden">
                                        <img :src="cart.product.img" class="card-img-top  product-card__img" :alt="cart.product.title" style="height: 100%; object-fit: cover">
                                    </div>
                                    <div class="card-body" style="display: flex; align-items: flex-start; justify-content: space-between; flex-direction: column">
                                        <h5 class="card-title" style="width: 100%; min-height: 48px; margin-bottom: 0; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-align: start"> @{{cart.product.title}} </h5>
                                        <div class="row" style="width: 100%; margin: 0; justify-content: space-between; align-items: center">
                                                <div class="col-8" style="display: flex; align-items: center; justify-content: space-between; flex-direction: row; padding: 0 !important;">
                                                    <button type="submit" class="btn  btn-primary" @click="DecrementCart(cart.product.id)" style="width: 40px">-</button>
                                                    <p class="card-text" style="font-weight: bold; margin: 0">@{{cart.count}} шт</p>
                                                    <button type="submit" class="btn  btn-primary" @click="AddToCart(cart.product.id)" style="width: 40px">+</button>
                                                </div>
                                                <p class="card-text  col-4" style="font-weight: bold; text-align: end; padding: 0 !important;">@{{cart.price}} ₽</p>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12  col-sm-12  col-md-12  col-xl-3  justify-content-end">
                    <h4 style="text-align: end">Сумма заказа:  @{{order.price}} ₽</h4>
                    <button type="submit" class="btn  btn-primary  col-12" data-bs-toggle="modal" data-bs-target="#OrderModal">Оформить заказ</button>
                </div>
            </div>
            <h4 class="mt-5" v-else style="font-size: 24px; text-align: center">Корзина пуста</h4>

        </div>

        <!-- Модальное окно оформления заказа -->
        <div class="modal  fade" id="OrderModal" tabindex="-1" aria-labelledby="OrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="OrderModalLabel">Оформление заказа</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12" :class="message ? 'mb-3  alert  alert-secondary  text-center' : ''">@{{ message }}</div>
                        <p class="modal-dialog">К сожалению, не все товары доступны в наличии в одном магазине. Выберите пожалуйста филиал для получения товара.</p>
                        <form id="OrderForm" enctype="multipart/form-data" @submit.prevent="Add">
                            <div class="mb-3" v-for="cart in carts">
                                <label for="shop_id" class="form-label"> @{{ cart.product.title }} </label>
                                <div class="col-12  mb-2">
                                    <select class="form-select" :name="`shop_id_${cart.id}`" :id="`shop_id_${cart.id}`">
                                        <template v-for="item in cart_with_shops">
                                            <option v-if="item.id == cart.id" :value="item.shop_id"> @{{ item.title }} </option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input type="password" class="form-control" id="password" name="password" :class="errors.password ? 'is-invalid' : ''">
                                <div :class="errors.password ? 'invalid-feedback' : ''" v-for="error in errors.password">
                                    @{{ error }}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" form="OrderForm" class="btn  btn-primary" @click="OrderArrange">Оформить</button>
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
                    carts: [],
                    cart_with_shops: [],
                    order: {},
                    errors: [],
                    message: ''
                }
            },
            methods: {
                //---Получение корзины
                async GetCarts() {
                    const response = await fetch('{{route('CartGet')}}');
                    const data = await response.json();
                    this.carts = data.carts;
                    this.order = data.order;
                },
                async GetCartsAndShops() {
                    const response = await fetch('{{route('GetCartsAndShops')}}');
                    const data = await response.json();
                    this.cart_with_shops = data.cart_with_shops;
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
                    this.GetCarts();
                },
                //---Уменьшение кол-ва товара в корзине
                async DecrementCart(id) {
                    const response = await fetch('{{route('CartDecrement')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id:id
                        })
                    });
                    this.GetCarts();
                },
                //---Удаление товара из корзины
                async DeleteToCart(id) {
                    console.log(id);
                    const response = await fetch('{{route('CartDelete')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id:id
                        })
                    });
                    this.GetCarts();
                },
                async OrderArrange() {
                    const form = document.querySelector('#OrderForm');
                    const formData = new FormData(form);
                    const response = await fetch('{{route('OrderArrange')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body: formData
                    });
                    if (response.status === 400) {
                        this.errors = await response.json();
                        setTimeout(()=>{this.errors = []}, 20000);
                    }
                    if (response.status === 200) {
                        window.location = response.url;
                    }
                    if (response.status === 403) {
                        this.message = await response.json();
                        setTimeout(()=>{this.message = null}, 20000);
                    }
                }
            },
            mounted() {
                this.GetCarts();
                this.GetCartsAndShops();
            }
        }
        Vue.createApp(ProductsFunctions).mount('#ProductsFunctions');
    </script>
@endsection

