<!DOCTYPE html>
<html>

<head>
    <title>Đăng nhập</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.tutorialjinni.com/bootstrap/5.2.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tutorialjinni.com/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="vh-100 d-flex justify-content-center align-items-center ">
        <div class="col-md-5 p-5 shadow-sm border rounded-5 border-primary bg-white">
            <h2 class="text-center mb-4 text-primary">Đăng nhập</h2>
            <form method="POST"  action="{{ route('admin.post_login') }}">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email:</label>
                    <input name="login" type="text" class="form-control border border-primary" id="exampleInputEmail1"
                        aria-describedby="emailHelp">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Mật khẩu</label>
                    <input name="password" type="password" class="form-control border border-primary" id="exampleInputPassword1">
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <p class="small"><a class="text-primary" href="forget-password.html">Quên mật khẩu?</a></p>
                <div class="d-grid">
                    <input class="btn btn-primary" type="submit" value="Đăng nhập">
                </div>
            </form>
            <div class="mt-3">
                <p class="mb-0  text-center">Bạn chưa có tài khoản? <a href="signup.html"
                        class="text-primary fw-bold">Đăng ký</a></p>
            </div>
        </div>
    </div>
</body>
</html>
