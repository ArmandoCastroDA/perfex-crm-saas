<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        your domain did not registered
    </title>
    <link rel="stylesheet" type="text/css" href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <link type="text/css" id="theme-opt" rel="stylesheet" href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/style.css">
</head>
<body>

<section class="bg-home bg-light d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="text-center">
                    <div class="icon d-flex align-items-center justify-content-center bg-soft-primary img-fluid avatar avatar-medium rounded-pill shadow-md d-block mx-auto">
                        <i class="uil uil-exclamation-triangle text-primary h1 mb-0"></i>
                    </div>
                    <h1 class="my-4 fw-bold text-danger">Your domain did not registered</h1>
                    <h4 class="text-muted para-desc mx-auto">Your domain did not registered.You need to register new
                        domain from <a href="<?= companyBaseUrl() ?>">here</a></h4>
                    <a href="<?= companyBaseUrl() ?>" class="btn btn-primary mt-3">Register New
                        Domain</a>

                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section>
</body>
</html>
