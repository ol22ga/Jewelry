@extends('layout.app')
@section('title')
    Главная
@endsection
@section('content')
    <div class="container">
        <div class="row  mt-5">
            <div class="col-12">
                <h2>Новинки</h2>
            </div>
            <div class="slider new">
                @foreach($new_products as $key=>$product)
                    <div style="background:  rgb(54, 106, 250); padding: 20px; margin: 10px">
                        <div style="max-height: 352px; overflow: hidden">
                            <img src="{{$product->img}}" class="card-img-top  product-card__img" alt="{{$product->title}}" style="height: 100%; object-fit: cover">
                        </div>
                        <p class="card-title  text-white" style="width: 100%; min-height: 60px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-align: center; margin-top: 20px; font-size: 20px">{{$product->title}}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row  mt-5">
            <div class="col-12">
                <h2>Типы изделий</h2>
            </div>
            <div class="slider type">
                    @foreach($types as $key=>$type)
                        <div onclick="RedirectCatalog({{$type->id}})" style="background:  rgb(54, 106, 250); padding: 20px; margin: 10px; cursor: pointer">
                            <div style="max-height: 352px; overflow: hidden">
                                <img src="{{$type->img}}" class="card-img-top  product-card__img" alt="{{$type->title}}" style="height: 100%; object-fit: cover">
                            </div>
                            <p class="card-title  text-white" style="width: 100%; min-height: 30px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-align: center; margin-top: 20px; font-size: 20px">{{$type->title}}</p>
                        </div>
                    @endforeach
            </div>
        </div>
        <div class="row  mt-5">
            <div class="col-12">
                <h2>Бестселлеры</h2>
            </div>
            <div class="slider bestseller">
                @foreach($bestsellers as $key=>$product)
                    <div style="background:  rgb(54, 106, 250); padding: 20px; margin: 10px">
                        <div style="max-height: 352px; overflow: hidden">
                            <img src="{{$product->img}}" class="card-img-top  product-card__img" alt="{{$product->title}}" style="height: 100%; object-fit: cover">
                        </div>
                        <p class="card-title  text-white" style="width: 100%; min-height: 60px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-align: center; margin-top: 20px; font-size: 20px">{{$product->title}}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .slick-prev:before, .slick-next:before {
            color: black;
            font-size: 36px;
        }
        .slick-dots li button:before {
            font-size: 10px;
        }
    </style>

    <script>
        $(".new").slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 3,
            slidesToScroll: 1
        });

        $(".type").slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 4,
            slidesToScroll: 1
        });

        $(".bestseller").slick({
            dots: true,
            infinite: true,
            speed: 500,
            slidesToShow: 3,
            slidesToScroll: 1
        });
    </script>

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
            equalHeight($(".item"));
        });
    </script>

    <script>
        function RedirectCatalog(id) {
            sessionStorage.setItem('type_id', id);
            window.location = 'http://jewelry/catalog';
        }
    </script>
@endsection
