@extends('layout.app')

@section('title', 'Товары')

@section('content')
    <div id="ProductPage">
        <!-- Основное контент -->
        <div class="container">
            <div class="row  mt-5">
                <div class="col-8">
                    <h2 class="text-primary">Товары</h2>
                </div>
                <div class="col-4  justify-content-around">
                    <button class="btn  btn-primary  col-12" data-bs-toggle="modal" data-bs-target="#AddModal">
                        Новый товар
                    </button>
                </div>
            </div>
            <div class="row  mt-5">
                <div class="col-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Тип</th>
                            <th scope="col">Бренд</th>
                            <th scope="col">Пол</th>
                            <th scope="col">Изображение</th>
                            <th scope="col">Название</th>
                            <th scope="col">Металл</th>
                            <th scope="col">Драгоценность</th>
{{--                            <th scope="col">Вес</th>--}}
{{--                            <th scope="col">Цена</th>--}}
                            <th scope="col" style="text-align: end">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(product, index) in products" style="vertical-align: bottom !important;">
                            <th scope="row"> @{{ index+1 }} </th>
                            <td> @{{ product.category_type.title }} </td>
                            <td> @{{ product.brand.title }} </td>
                            <td> @{{ product.category_gender.title }} </td>
                            <td style="display: flex; align-items: flex-start; justify-content: space-between">
                                <div style="width: 80px; height: 80px; overflow: hidden">
                                    <img :src="product.img" :alt="product.title" style="width: 100%; height: auto; object-fit: cover">
                                </div>
                            </td>
                            <td> @{{ product.title }} </td>
                            <td> @{{ product.category_metal.title }} </td>
                            <td> @{{ product.category_jewel.title }} </td>
{{--                            <td> @{{ product.weight }} г.</td>--}}
{{--                            <td> @{{ product.price }} ₽</td>--}}
                            <td>
                                <div style="display: flex; align-items: center; justify-content: flex-end">
                                    <button type="button" class="btn  btn-primary" @click="RenderModalShow(product.id)" data-bs-toggle="modal" data-bs-target="#ShowModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none; margin-right: 5px;">
                                        <iconify-icon icon="zondicons:view-show" style="color: white;" width="20" height="20"></iconify-icon>
                                    </button>
                                    <button type="button" class="btn  btn-warning" @click="RenderModalAvailable(product.id)" data-bs-toggle="modal" data-bs-target="#AvailableModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none; margin-right: 5px;">
                                        <iconify-icon icon="healthicons:geo-location" style="color: white;" width="20"></iconify-icon>
                                    </button>
                                    <button type="button" class="btn  btn-success" @click="RenderModalEdit(product.id)" data-bs-toggle="modal" data-bs-target="#EditModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none; margin-right: 5px;">
                                        <iconify-icon icon="mdi:lead-pencil" style="color: white;" width="20" height="20"></iconify-icon>
                                    </button>
                                    <button type="button" class="btn  btn-danger" @click="RenderModalDelete(product.id)" data-bs-toggle="modal" data-bs-target="#DeleteModal"  style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem; border: none">
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
                        <h5 class="modal-title" id="AddModalLabel">Добавление товара</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <form id="FormAdd" enctype="multipart/form-data" @submit.prevent="Add">
                            <div class="mb-3">
                                <label for="category_type_id" class="form-label">Тип изделия</label>
                                <select class="form-select" name="category_type_id" id="category_type_id">
                                    <option v-for="type in types" :value="type.id"> @{{type.title}} </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Бренд</label>
                                <select class="form-select" name="brand_id" id="brand_id">
                                    <option v-for="brand in brands" :value="brand.id"> @{{brand.title}} </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Название</label>
                                <input type="text" class="form-control" id="title" name="title" :class="errors.title ? 'is-invalid' : ''">
                                <div :class="errors.title ? 'invalid-feedback' : ''" v-for="error in errors.title">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Цена</label>
                                <input type="text" class="form-control" id="price" name="price" :class="errors.price ? 'is-invalid' : ''">
                                <div :class="errors.price ? 'invalid-feedback' : ''" v-for="error in errors.price">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <div style="display: flex; align-items: center; justify-content: space-between">
                                    <label class="form-label" style="margin: 0 !important;">Пол </label>
                                    <div v-for="gender in genders" style="display: flex; align-items: center; justify-content: space-between">
                                        <input type="radio" name="category_gender_id" :id="gender.title" :value="gender.id" style="margin-right: 10px" checked>
                                        <label :for="gender.title" class="form-label" style="margin: 0 !important;"> @{{gender.title}} </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea class="form-control" id="description" name="description" :class="errors.description ? 'is-invalid' : ''" style="max-height: 300px"></textarea>
                                <div :class="errors.description ? 'invalid-feedback' : ''" v-for="error in errors.description">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3  row">
                                <div class="col-6">
                                    <label for="category_metal_id" class="form-label">Металл</label>
                                    <select class="form-select" name="category_metal_id" id="category_metal_id">
                                        <option v-for="metal in metals" :value="metal.id"> @{{metal.title}} </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="category_jewel_id" class="form-label">Драгоценность</label>
                                    <select class="form-select" name="category_jewel_id" id="category_jewel_id">
                                        <option v-for="jewel in jewels" :value="jewel.id"> @{{jewel.title}} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="weight" class="form-label">Вес</label>
                                <input type="text" class="form-control" id="weight" name="weight" :class="errors.weight ? 'is-invalid' : ''">
                                <div :class="errors.weight ? 'invalid-feedback' : ''" v-for="error in errors.weight">
                                    @{{ error }}
                                </div>
                            </div>
                            <div>
                                <label for="img" class="form-label">Изображение</label>
                                <input type="file" class="form-control" id="img" name="img" :class="errors.img ? 'is-invalid' : ''">
                                <div :class="errors.img ? 'invalid-feedback' : ''" v-for="error in errors.img">
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
                        <h5 class="modal-title" id="DeleteModalLabel">Удаление товара</h5>
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
                        <form id="FormEdit" enctype="multipart/form-data" @submit.prevent="Edit">
                            <div class="mb-3">
                                <label for="EditModalSelectType" class="form-label">Тип изделия</label>
                                <select class="form-select" name="category_type_id" id="EditModalSelectType">
                                    <option v-for="type in types" :value="type.id"> @{{type.title}} </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="EditModalSelectBrand" class="form-label">Бренд</label>
                                <select class="form-select" name="brand_id" id="EditModalSelectBrand">
                                     <option v-for="brand in brands" :value="brand.id"> @{{brand.title}} </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="EditModalInputTitle" class="form-label">Название</label>
                                <input type="text" class="form-control" id="EditModalInputTitle" name="title" :class="errors.title ? 'is-invalid' : ''">
                                <div :class="errors.title ? 'invalid-feedback' : ''" v-for="error in errors.title">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="EditModalInputTitlePrice" class="form-label">Цена</label>
                                <input type="text" class="form-control" id="EditModalInputTitlePrice" name="price" :class="errors.price ? 'is-invalid' : ''">
                                <div :class="errors.price ? 'invalid-feedback' : ''" v-for="error in errors.price">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <div style="display: flex; align-items: center; justify-content: space-between">
                                    <label class="form-label" style="margin: 0 !important;">Пол </label>
                                    <div v-for="gender in genders" style="display: flex; align-items: center; justify-content: space-between">
                                        <input type="radio" name="category_gender_id" :id="`EditModalRadioGender${gender.id}`" :value="gender.id" style="margin-right: 10px" checked>
                                        <label :for="`EditModalRadioGender${gender.id}`" class="form-label" style="margin: 0 !important;"> @{{gender.title}} </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="EditModalTextareaDescription" class="form-label">Описание</label>
                                <textarea class="form-control" id="EditModalTextareaDescription" name="description" :class="errors.description ? 'is-invalid' : ''" style="max-height: 300px"></textarea>
                                <div :class="errors.description ? 'invalid-feedback' : ''" v-for="error in errors.description">
                                    @{{ error }}
                                </div>
                            </div>
                            <div class="mb-3  row">
                                <div class="col-6">
                                    <label for="EditModalSelectMetal" class="form-label">Металл</label>
                                    <select class="form-select" name="category_metal_id" id="EditModalSelectMetal">
                                        <option v-for="metal in metals" :value="metal.id"> @{{metal.title}} </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="EditModalSelectJewel" class="form-label">Драгоценность</label>
                                    <select class="form-select" name="category_jewel_id" id="EditModalSelectJewel">
                                        <option v-for="jewel in jewels" :value="jewel.id"> @{{jewel.title}} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="EditModalInputWeight" class="form-label">Вес</label>
                                <input type="text" class="form-control" id="EditModalInputWeight" name="weight" :class="errors.weight ? 'is-invalid' : ''">
                                <div :class="errors.weight ? 'invalid-feedback' : ''" v-for="error in errors.weight">
                                    @{{ error }}
                                </div>
                            </div>
                            <div>
                                <label for="EditModalInputImg" class="form-label">Изображение</label>
                                <input type="file" class="form-control" id="EditModalInputImg" name="img" :class="errors.img ? 'is-invalid' : ''">
                                <div :class="errors.img ? 'invalid-feedback' : ''" v-for="error in errors.img">
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

        <!-- Модальное окно просмотра -->
        <div class="modal  fade" id="ShowModal" tabindex="-1" aria-labelledby="ShowModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ShowModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body" id="ShowModalBody" style="display: flex; flex-direction: column; justify-content: start; align-items: center">
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно наличия -->
        <div class="modal  fade" id="AvailableModal" tabindex="-1" aria-labelledby="AvailableModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AvailableModalLabel">Наличие в магазинах</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body" id="AvailableModalBody">
                        <div class="row">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Магазин</th>
                                        <th scope="col" style="text-align: end">Кол-во</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in available" style="vertical-align: bottom !important;">
                                        <td> @{{ item.shop.title }} </td>
                                        <td style="text-align: end"> @{{ item.count }} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <form id="FormAvailable" @submit.prevent="AvailableEdit()" class="mt-2">
                                <div class="mb-3">
                                    <label for="shop_id" class="form-label">Магазин</label>
                                    <select class="form-select" name="shop_id" id="shop_id">
                                        <option v-for="shop in shops" :value="shop.id"> @{{shop.title}} </option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="count" class="form-label">Количество</label>
                                    <input type="number" class="form-control" id="count" name="count" :class="errors.count ? 'is-invalid' : ''">
                                    <div :class="errors.count ? 'invalid-feedback' : ''" v-for="error in errors.count">
                                        @{{ error }}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" form="FormAvailable" class="btn  btn-primary">Изменить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ProductFunctions = {
            data() {
                return {
                    products: [],
                    brands: [],
                    types: [],
                    metals: [],
                    jewels: [],
                    genders: [],
                    shops: [],
                    products_and_shops: [],

                    available: [],

                    errors: [],
                    message: '',
                    active: ''
                }
            },
            methods: {
                //---Получение товаров
                async GetProducts() {
                    const response = await fetch('{{route('ProductGet')}}');
                    const data = await response.json();
                    this.products = data.all;
                    console.log(this.products);
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
                    console.log(this.products_and_shops);
                },
                //---Добавление нового товара
                async Add() {
                    const form = document.querySelector('#FormAdd');
                    const formData = new FormData(form);
                    const response = await fetch('{{route('ProductAdd')}}', {
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
                    var title = this.products.find(product => product.id === id).title;
                    document.getElementById('DeleteModalBody').innerText = 'Вы уверены, что хотите удалить товар "' + title + '"';
                    this.active = this.products.find(product => product.id === id).id;
                },
                //---Удаление товара
                async Delete() {
                    const response = await fetch('{{route('ProductDelete')}}', {
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
                    var title = this.products.find(product => product.id === id).title;
                    var gender = 'EditModalRadioGender' + this.products.find(product => product.id === id).category_gender_id;
                    document.getElementById('EditModalLabel').innerText = 'Редактирование товара "' + title + '"';
                    document.getElementById('EditModalInputTitle').value = title;
                    document.getElementById('EditModalInputTitlePrice').value = this.products.find(product => product.id === id).price;
                    document.getElementById('EditModalTextareaDescription').value = this.products.find(product => product.id === id).description;
                    document.getElementById('EditModalSelectType').value = this.products.find(product => product.id === id).category_type_id;
                    document.getElementById('EditModalSelectBrand').value = this.products.find(product => product.id === id).brand_id;
                    document.getElementById('EditModalSelectMetal').value = this.products.find(product => product.id === id).category_metal_id;
                    document.getElementById('EditModalSelectJewel').value = this.products.find(product => product.id === id).category_jewel_id;
                    document.getElementById('EditModalInputWeight').value = this.products.find(product => product.id === id).weight;
                    document.getElementById(gender).checked = true;
                    this.active = this.products.find(product => product.id === id).id;
                },
                //---Редактирование товара
                async Edit() {
                    const form = document.querySelector('#FormEdit');
                    const formData = new FormData(form);
                    formData.append('id', this.active);
                    const response = await fetch('{{route('ProductEdit')}}', {
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
                },
                //---Отрисовка модального окна наличия товара
                RenderModalAvailable(id) {
                    this.available = (this.products_and_shops).filter(item => {
                        return item.product_id == id;
                    });

                    var title = this.products.find(product => product.id === id).title;
                    document.getElementById('AvailableModalLabel').innerText = 'Наличие товара "' + title + '" в магазинах';

                    this.active = this.products.find(product => product.id === id).id;
                },
                //---Редактирование наличия товара
                async AvailableEdit() {
                    const form = document.querySelector('#FormAvailable');
                    const formData = new FormData(form);
                    formData.append('product_id', this.active);
                    const response = await fetch('{{route('AvailableEdit')}}', {
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
                this.GetProducts();
                this.GetBrands();
                this.GetTypes();
                this.GetMetals();
                this.GetJewels();
                this.GetGenders();
                this.GetShops();
                this.GetProductsAndShops();
            }
        }
        Vue.createApp(ProductFunctions).mount('#ProductPage');
    </script>
@endsection
