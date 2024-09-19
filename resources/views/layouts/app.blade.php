<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hải Minh Bus</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://utt.edu.vn/home/images/stories/logo-utt-border.png">
</head>

<body>
    <!-- Header -->
    @include("Layouts.header")

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    @include("Layouts.footer")
</body>

</html>