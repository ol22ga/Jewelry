@extends('layout.app')

@section('title', 'Мои заказы')

@section('content')
    <div id="CategoryPage">
        <!-- Основное контент -->
        <div class="container">
            <div class="row  mt-5">
                <div class="col-8">
                    <h2 class="text-primary">Мои заказы</h2>
                </div>
                <div class="col-4  justify-content-around">
                    <div class="mb-5  col-12  justify-content-center">
                        <div class="col-12  mb-2">
                            <select class="form-select" name="" id="status" v-model="status">
                                <option value="0">все</option>
                                <option value="в обработке">в обработке</option>
                                <option value="одобрен">одобрен</option>
                                <option value="отклонен">отклонен</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row  mt-5">
                <div class="col-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Стоимость</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Комментарий администратора</th>
                            <th scope="col">Дата создания</th>
                            <th scope="col" style="text-align: end">Детали</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(order, index) in FilterOrders" style="vertical-align: bottom !important;">
                            <th scope="row"> @{{ index+1 }} </th>
                            <td> @{{ order.price }} ₽</td>
                            <td> @{{ order.status }} </td>
                            <td>
                                <span v-if="order.comment == null">
                                    -
                                </span>
                                <span v-else>
                                    @{{ order.comment }}
                                </span>
                            </td>
                            <td> @{{ order.created_at }} </td>
                            <td>
                                <div style="display: flex; align-items: center; justify-content: flex-end">
                                    <button type="button" class="btn  btn-primary" @click="RenderModalShow(order.id)" data-bs-toggle="modal" data-bs-target="#ShowModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none; margin-right: 5px;">
                                        <iconify-icon icon="zondicons:view-show" style="color: white;" width="20" height="20"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
                        <div class="row">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Товар</th>
                                        <th scope="col" style="text-align: end; min-width: 90px">Кол-во</th>
                                        <th scope="col" style="text-align: end; min-width: 90px">Цена</th>
                                        <th scope="col" style="text-align: end">Магазин</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="cart in active" style="vertical-align: bottom !important;">
                                        <td> @{{ cart.product.title }} </td>
                                        <td style="text-align: end"> @{{ cart.count }} шт.</td>
                                        <td style="text-align: end"> @{{ cart.price }} ₽</td>
                                        <td style="text-align: end"> @{{ cart.shop.title }} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CategoryFunctions = {
            data() {
                return {
                    orders: [],
                    carts: [],
                    active: [],
                    status: 0
                }
            },
            methods: {
                //---Получение заказов
                async GetOrders() {
                    const response = await fetch('{{route('OrderGet')}}');
                    const data = await response.json();
                    this.orders = data.orders_user;
                    console.log(this.orders);
                },
                //---Получение корзины
                async GetCarts() {
                    const response = await fetch('{{route('CartGet')}}');
                    const data = await response.json();
                    this.carts = data.carts_all;
                    console.log(this.carts);
                },
                //---Отрисовка модального окна с деталями заказа
                RenderModalShow(id) {
                    this.active = (this.carts).filter(cart => {
                        return cart.order_id == id;
                    });
                }

            },
            computed: {
                FilterOrders() {
                    return this.orders
                        .filter(order => {
                            return this.status == 0 || order.status == this.status;
                        })
                }
            },
            mounted() {
                this.GetOrders();
                this.GetCarts();
            }
        }
        Vue.createApp(CategoryFunctions).mount('#CategoryPage');
    </script>
@endsection
