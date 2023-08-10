@extends('layout.app')
@section('title')
    Регистрация
@endsection
@section('content')
    <div class="container" id="Registration">
        <div class="row  mt-5  col-12  text-center  text-primary"><h2>Регистрация</h2></div>
        <div class="row  mt-5  justify-content-center">
            <div class="col-6">
                <form id="formRegistration" @submit.prevent="Registration">
                    <div class="mb-3">
                        <label for="fio" class="form-label">ФИО</label>
                        <input type="text" class="form-control" id="fio" name="fio" :class="errors.fio ? 'is-invalid' : ''" title="Это поле обязательное,&#013;может содержать только кириллицу, пробел, тире">
                        <div :class="errors.fio ? 'invalid-feedback' : ''" v-for="error in errors.fio">
                            @{{ error }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Дата рождения</label>
                        <input type="date" class="form-control" id="birthday" name="birthday" :class="errors.birthday ? 'is-invalid' : ''" title="Это поле обязательное">
                        <div :class="errors.birthday ? 'invalid-feedback' : ''" v-for="error in errors.birthday">
                            @{{ error }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="phone" name="phone" :class="errors.phone ? 'is-invalid' : ''" title="Это поле обязательное,&#013;должно содержать номер телефона(11 символов)">
                        <div :class="errors.phone ? 'invalid-feedback' : ''" v-for="error in errors.phone">
                            @{{ error }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" :class="errors.email ? 'is-invalid' : ''" title="Это поле обязательное,&#013;может содержать только адрес электронной почты">
                        <div :class="errors.email ? 'invalid-feedback' : ''" v-for="error in errors.email">
                            @{{ error }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password" :class="errors.password ? 'is-invalid' : ''" title="Это поле обязательное,&#013;минимальная длина пароля - 6 символов">
                        <div :class="errors.password ? 'invalid-feedback' : ''" v-for="error in errors.password">
                            <template  v-if="error !== 'Пароли не совпадают'">
                                @{{ error }}
                            </template>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Повторите пароль</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" :class="errors.password ? 'is-invalid' : ''">
                        <div :class="errors.password ? 'invalid-feedback' : ''" v-for="error in errors.password">
                            <template  v-if="error == 'Пароли не совпадают'">
                                @{{ error }}
                            </template>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rules" name="rules" :class="errors.rules ? 'is-invalid' : ''">
                        <label class="form-check-label" for="rules">Согласие на обработку персональных данных</label>
                        <div :class="errors.rules ? 'invalid-feedback' : ''" v-for="error in errors.rules">
                            @{{ error }}
                        </div>
                    </div>
                    <button type="submit" class="btn  btn-primary  col-12">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const Registration = {
            data() {
                return {
                    errors: [],
                }
            },
            methods:{
                async Registration() {
                    const form = document.querySelector('#formRegistration');
                    const formData = new FormData(form);
                    const response = await fetch('{{route('Registration')}}', {
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN' : '{{csrf_token()}}',
                        },
                        body: formData
                    });
                    if(response.status === 400) {
                        this.errors = await response.json();
                    }
                    if (response.status === 200) {
                        window.location = response.url;
                    }
                }
            }
        }
        Vue.createApp(Registration).mount('#Registration')
    </script>
{{--    <style>--}}
{{--        .form-check-input:checked {--}}
{{--            background-color: #212529;--}}
{{--            border-color: #212529;--}}
{{--        }--}}
{{--        .form-check-input:focus {--}}
{{--            box-shadow:0 0 0 .25rem rgba(33, 37, 41, 0.3);--}}
{{--            border-color: rgba(33, 37, 41, 0.3);--}}
{{--        }--}}
{{--    </style>--}}
@endsection
