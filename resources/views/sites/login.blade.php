<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background-color: #aabed2;
            backdrop-filter: blur(10px);
        }

        .login-container {
            max-width: 420px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-container h3 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-login {
            width: 100%;
            border-radius: 8px;
        }

        .social-login button {
            width: 100%;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .small-text {
            text-align: center;
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }

        .small-text a {
            color: #007bff;
            text-decoration: none;
        }

        .small-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="login-container">
            <h3>Đăng Nhập</h3>

            <form>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="Nhập email của bạn">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" id="password" class="form-control" placeholder="Nhập mật khẩu">
                </div>

                <button type="submit" class="btn btn-dark btn-login">Đăng Nhập</button>

                <span class="small-text">
                    <a href="#">Quên mật khẩu?</a>
                </span>
                <span class="small-text">
                    Chưa có tài khoản? <a href="#">Đăng ký ngay</a>
                </span>
            </form>

            <hr>

            <div class="social-login">
                <button class="btn btn-danger"><i class="fa-brands fa-google pe-1"></i> Đăng nhập bằng Google</button>
                <button class="btn btn-primary"><i class="fa-brands fa-facebook pe-1"></i> Đăng nhập bằng Facebook</button>
            </div>
        </div>
    </div>
</body>
</html>
