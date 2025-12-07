<!DOCTYPE html>
<html>

<head>
    @include('home.css')
</head>

<body>
    <div class="hero_area">
        <!-- Header -->
        @include('home.header')

        <!-- Slider Section -->
        @include('home.slider')

    </div>

    <!-- shop section -->
    @include('home.product')

    <!-- end shop section -->


    <!-- contact section -->

    @include('home.contact')

    <!-- end contact section -->

    <!-- info section -->

    @include('home.footer')

</body>

</html>
