@extends('layout.app')

@section('title', 'Магазины')

@section('content')
    <div id="ShopPage">
        <!-- Основное контент -->
        <div class="container">
            <div class="row  mt-5">
                <div class="col-8">
                    <h2 class="text-primary">Магазины</h2>
                </div>
                <div class="col-4  justify-content-around">
                    <button class="btn  btn-primary  col-12" data-bs-toggle="modal" data-bs-target="#AddModal">
                        Новый магазин
                    </button>
                </div>
            </div>
            <div class="row  mt-5">
                <div class="col-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Название</th>
                            <th scope="col">Адрес</th>
                            <th scope="col">Время открытия</th>
                            <th scope="col">Время закрытия</th>
                            <th scope="col">Рабочие дни</th>
                            <th scope="col" style="text-align: end">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(shop, index) in shops" style="vertical-align: bottom !important;">
                            <th scope="row"> @{{ index+1 }} </th>
                            <td> @{{ shop.title }} </td>
                            <td> @{{ shop.address }} </td>
                            <td> @{{ shop.time_start }} </td>
                            <td> @{{ shop.time_end }} </td>
                            <td> @{{ shop.days }} </td>
                            <td>
                                <div style="display: flex; align-items: center; justify-content: flex-end">
                                    <button type="button" class="btn  btn-success" @click="RenderModalEdit(shop.id)" data-bs-toggle="modal" data-bs-target="#EditModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none; margin-right: 5px;">
                                        <iconify-icon icon="mdi:lead-pencil" style="color: white;" width="20" height="20"></iconify-icon>
                                    </button>
                                    <button type="button" class="btn  btn-danger" @click="RenderModalDelete(shop.id)" data-bs-toggle="modal" data-bs-target="#DeleteModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none">
                                        <iconify-icon icon="material-symbols:delete" style="color: white;" width="20"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Модальное окно добавления -->
        <div class="modal  fade" id="AddModal" tabindex="-1" aria-labelledby="AddModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddModalLabel">Добавление магазина</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <form id="FormAdd" @submit.prevent="Add">
                            <div class="mb-3">
                                <label for="title" class="form-label">Название</label>
                                <input type="text" class="form-control" id="title" name="title" :class="errors.title ? 'is-invalid' : ''">
                                <div :class="errors.title ? 'invalid-feedback' : ''" v-for="error in errors.title">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Адрес</label>
                                <input type="text" class="form-control" id="address" name="address" :class="errors.address ? 'is-invalid' : ''">
                                <div :class="errors.address ? 'invalid-feedback' : ''" v-for="error in errors.address">
                                    @{{ error }}
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between">
                                <div class="mb-3  col-5">
                                    <label for="time_start" class="form-label">Время открытия</label>
                                    <input type="time" class="form-control" id="time_start" name="time_start" :class="errors.time_start ? 'is-invalid' : ''">
                                    <div :class="errors.time_start ? 'invalid-feedback' : ''" v-for="error in errors.time_start">
                                        @{{ error }}
                                    </div>
                                </div>
                                <div class="mb-3  col-5">
                                    <label for="time_end" class="form-label">Время закрытия</label>
                                    <input type="time" class="form-control" id="time_end" name="time_end" :class="errors.time_end ? 'is-invalid' : ''">
                                    <div :class="errors.time_end ? 'invalid-feedback' : ''" v-for="error in errors.time_end">
                                        @{{ error }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="days" class="form-label">Рабочие дни</label>
                                <input type="text" class="form-control" id="days" name="days" :class="errors.days ? 'is-invalid' : ''">
                                <div :class="errors.days ? 'invalid-feedback' : ''" v-for="error in errors.days">
                                    @{{ error }}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" form="FormAdd" class="btn  btn-primary">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно удаления -->
        <div class="modal  fade" id="DeleteModal" tabindex="-1" aria-labelledby="DeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DeleteModalLabel">Удаление магазина</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body" id="DeleteModalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button @click="Delete" class="btn  btn-primary">Удалить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно редактирования -->
        <div class="modal  fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <span></span>
                        <form id="FormEdit" enctype="multipart/form-data" @submit.prevent="Edit">
                            <div class="mb-3">
                                <label for="EditModalInputTitle" class="form-label">Название</label>
                                <input type="text" class="form-control" id="EditModalInputTitle" name="title" :class="errors.title ? 'is-invalid' : ''">
                                <div :class="errors.title ? 'invalid-feedback' : ''" v-for="error in errors.title">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="EditModalInputAddress" class="form-label">Адрес</label>
                                <input type="text" class="form-control" id="EditModalInputAddress" name="address" :class="errors.address ? 'is-invalid' : ''">
                                <div :class="errors.address ? 'invalid-feedback' : ''" v-for="error in errors.address">
                                    @{{ error }}
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between">
                                <div class="mb-3  col-5">
                                    <label for="EditModalInputTimeStart" class="form-label">Время открытия</label>
                                    <input type="time" class="form-control" id="EditModalInputTimeStart" name="time_start" :class="errors.time_start ? 'is-invalid' : ''">
                                    <div :class="errors.time_start ? 'invalid-feedback' : ''" v-for="error in errors.time_start">
                                        @{{ error }}
                                    </div>
                                </div>
                                <div class="mb-3  col-5">
                                    <label for="EditModalInputTimeEnd" class="form-label">Время закрытия</label>
                                    <input type="time" class="form-control" id="EditModalInputTimeEnd" name="time_end" :class="errors.time_end ? 'is-invalid' : ''">
                                    <div :class="errors.time_end ? 'invalid-feedback' : ''" v-for="error in errors.time_end">
                                        @{{ error }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="EditModalInputDays" class="form-label">Рабочие дни</label>
                                <input type="text" class="form-control" id="EditModalInputDays" name="days" :class="errors.days ? 'is-invalid' : ''">
                                <div :class="errors.days ? 'invalid-feedback' : ''" v-for="error in errors.days">
                                    @{{ error }}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" form="FormEdit" class="btn  btn-primary">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

    <script>
        const ShopFunctions = {
            data() {
                return {
                    shops: [],
                    errors: [],
                    message: '',
                    active: ''
                }
            },
            methods: {
                //---Получение
                async Get() {
                    const response = await fetch('{{route('ShopGet')}}');
                    const data = await response.json();
                    this.shops = data.all;
                },
                //---Добавление
                async Add() {
                    const form = document.querySelector('#FormAdd');
                    const formData = new FormData(form);
                    const response = await fetch('{{route('ShopAdd')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        body: formData
                    });
                    if (response.status === 400) {
                        this.errors = await response.json();
                        setTimeout(()=>{
                            this.errors = []
                        }, 5000);
                    };
                    if (response.status === 200) {
                        window.location = response.url;
                    };
                },
                //---Отрисовка модального окна удаления
                RenderModalDelete(id) {
                    var title = this.shops.find(shop => shop.id === id).title;
                    document.getElementById('DeleteModalBody').innerText = 'Вы уверены, что хотите удалить магазин "' + title + '"';
                    this.active = this.shops.find(shop => shop.id === id).id;
                },
                //---Удаление
                async Delete() {
                    const response = await fetch('{{route('ShopDelete')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({id:this.active})
                    });
                    if (response.status === 200) {
                        window.location = response.url;
                    };
                },
                //---Отрисовка модального окна редактирования
                RenderModalEdit(id) {
                    var title = this.shops.find(shop => shop.id === id).title;
                    var address = this.shops.find(shop => shop.id === id).address;
                    var time_start = this.shops.find(shop => shop.id === id).time_start;
                    var time_end = this.shops.find(shop => shop.id === id).time_end;
                    var days = this.shops.find(shop => shop.id === id).days;
                    document.getElementById('EditModalLabel').innerText = 'Редактирование магазина "' + title + '"';
                    document.getElementById('EditModalInputTitle').value = title;
                    document.getElementById('EditModalInputAddress').value = address;
                    document.getElementById('EditModalInputTimeStart').value = time_start;
                    document.getElementById('EditModalInputTimeEnd').value = time_end;
                    document.getElementById('EditModalInputDays').value = days;
                    this.active = this.shops.find(shop => shop.id === id).id;
                },
                //---Редактирование
                async Edit() {
                    const form = document.querySelector('#FormEdit');
                    const formData = new FormData(form);
                    formData.append('id', this.active);
                    const response = await fetch('{{route('ShopEdit')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        body: formData
                    });
                    if (response.status === 400) {
                        this.errors = await response.json();
                        setTimeout(()=>{
                            this.errors = []
                        }, 5000);
                    }
                    if (response.status === 200) {
                        window.location = response.url;
                    }
                }
            },
            mounted() {
                this.Get();
            }
        }
        Vue.createApp(ShopFunctions).mount('#ShopPage');
    </script>
@endsection
