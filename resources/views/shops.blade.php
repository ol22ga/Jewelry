@extends('layout.app')

@section('title', 'Магазины')

@section('content')
    <div id="ShopPage">
        <!-- Основное контент -->
        <div class="container">
            <div class="row  mt-5  col-12  text-center"><h2>Магазины</h2></div>
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
                                    <button type="button" class="btn  btn-primary" @click="RedirectCatalog(shop.id)" data-bs-toggle="modal" data-bs-target="#EditModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none; margin-right: 5px;">
                                        <iconify-icon icon="icon-park-outline:go-on" style="color: white;" width="20"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <script>
            const ShopFunctions = {
                data() {
                    return {
                        shops: [],
                    }
                },
                methods: {
                    //---Получение
                    async Get() {
                        const response = await fetch('{{route('ShopGet')}}');
                        const data = await response.json();
                        this.shops = data.all;
                    },
                    async RedirectCatalog(id) {
                        sessionStorage.setItem('shop_id', id);
                        const response = await fetch('{{route('CatalogPage')}}');
                        window.location = response.url;
                    }
                },
                mounted() {
                    this.Get();
                }
            }
            Vue.createApp(ShopFunctions).mount('#ShopPage');
        </script>
@endsection
