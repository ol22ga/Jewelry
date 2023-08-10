@extends('layout.app')

@section('title', 'Заказы')

@section('content')
    <div id="CategoryPage">
        <!-- Основное контент -->
        <div class="container">
            <div class="row  mt-5">
                <div class="col-8">
                    <h2 class="text-primary">Заказы</h2>
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
            <div class="row  justify-content-center">
                <div class="col-12" :class="message ? 'mt-5  alert  alert-secondary  text-center' : ''" style="margin-bottom: 0 !important;">@{{ message }}</div>
            </div>
            <div class="row  mt-5">
                <div class="col-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ФИО заказчика</th>
                            <th scope="col">Стоимость</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Комментарий</th>
                            <th scope="col">Дата создания</th>
                            <th scope="col" style="text-align: end">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(order, index) in FilterOrders" style="vertical-align: bottom !important;">
                            <th scope="row"> @{{ index+1 }} </th>
                            <td> @{{ order.user.fio }} </td>
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
                                    <button v-if="order.status == 'в обработке'" type="button" class="btn  btn-success" @click="OrderApproved(order.id)" data-bs-toggle="modal" data-bs-target="#EditModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none; margin-right: 5px;">
                                        <iconify-icon icon="material-symbols:done-sharp" style="color: white;" width="25"></iconify-icon>
                                    </button>
                                    <button v-if="order.status == 'в обработке'" type="button" class="btn  btn-danger" @click="RenderModalOrderRejected(order.id)" data-bs-toggle="modal" data-bs-target="#OrderRejectedModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none">
                                        <iconify-icon icon="radix-icons:cross-2" style="color: white;" width="25"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Модальное окно отмены заказа -->
        <div class="modal  fade" id="OrderRejectedModal" tabindex="-1" aria-labelledby="OrderRejectedModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="OrderRejectedModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <form id="FormOrderRejected" @submit.prevent="OrderRejected">
                            <div class="mb-3">
                                <label for="comment" class="form-label">Причина отказа</label>
                                <textarea class="form-control" id="comment" name="comment" :class="errors.comment ? 'is-invalid' : ''" style="max-height: 300px"></textarea>
                                <div :class="errors.comment ? 'invalid-feedback' : ''" v-for="error in errors.comment">
                                    @{{ error }}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" form="FormOrderRejected" class="btn  btn-primary">Сохранить</button>
                    </div>
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
                    errors: [],
                    message: '',
                    status: 0
                }
            },
            methods: {
                //---Получение заказов
                async GetOrders() {
                    const response = await fetch('{{route('OrderGet')}}');
                    const data = await response.json();
                    this.orders = data.orders_all;
                    console.log(this.orders);
                },
                //---Получение корзины
                async GetCarts() {
                    const response = await fetch('{{route('CartGet')}}');
                    const data = await response.json();
                    this.carts = data.carts_all;
                    console.log(this.carts);
                },
                //---Подтверждение заказа
                async OrderApproved(id) {
                    const response = await fetch('{{route('OrderApproved')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    });
                    if (response.status === 200) {
                        this.message = await response.json();
                        setTimeout(()=>{this.message = ''}, 20000);
                    }
                    if (response.status === 400) {
                        this.message = await response.json();
                        setTimeout(()=>{this.message = ''}, 20000);
                    }
                    this.GetOrders();
                },
                //---Отрисовка модального окна отмены заказа
                RenderModalOrderRejected(id) {
                    document.getElementById('OrderRejectedModalLabel').innerText = 'Отмена заказа №' + id;
                    this.active = id;
                },
                //---Отмена заказа
                async OrderRejected() {
                    const form = document.querySelector('#FormOrderRejected');
                    const formData = new FormData(form);
                    formData.append('id', this.active);
                    const response = await fetch('{{route('OrderRejected')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        body: formData
                    });
                    if (response.status === 200) {
                        this.GetOrders();
                        this.message = await response.json();
                        setTimeout(()=>{this.message = ''}, 20000);
                    }
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
