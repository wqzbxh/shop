<!DOCTYPE html>
<html>
<head>
    <title>打印测试</title>
</head>
<style>
    @media print {
        .page-template {
            page-break-after: always; /* 在每个 .page-template 元素之后添加分页符 */
        }

        /* 设置按钮在打印时不可见 */
        button {
            display: none;
        }

        /* 设置表格样式 */
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black; /* 添加表格边框 */
        }

        th, td {
            border: 1px solid #cccccc;
            padding: 8px;

        }

        th {
            background-color: #f2f2f2;
        }
        /* 设置打印页面样式 */
        @media print {
            /* 隐藏页眉 */
            header {
                display: none;
            }

            /* 隐藏页脚 */
            footer {
                display: none;
            }

            /* 隐藏底部网址 */
            body::after {
                content: none;
            }
        }
    }
</style>
<body>
@foreach ($users as $user)
    <div class="page-template">
        <h1>{{$user->realname}}</h1>
        <table>
            <tr>
                <th>用户名称</th>
                <td>{{$user->name}}</td>
            </tr>
            <tr>
                <th>加盐密码</th>
                <td>{{$user->password}}</td>
            </tr>
            <tr>
                <th>邮箱</th>
                <td>{{$user->email}}</td>
            </tr>
            <tr>
                <th>手机号</th>
                <td>{{$user->phone}}</td>
            </tr>
            <tr>
                <th>身份证</th>
                <td>{{$user->idcard}}</td>
            </tr>
        </table>
    </div>

@endforeach



<button onclick="printPage()">分页打印</button>

<script>
    function printPage() {
        window.print();
    }
</script>
</body>
</html>
php artisan make:resource TimeProjectResource
PS F:\project\kcxt\shopGIt> php artisan make:resource GoodsTypeResource


<!-- CSS的@media print媒体查询来控制打印样式。
page-break-after: always; 属性被应用于每个 .page-template 元素，这样在每个模板页面之后都会添加分页符，确保每个模板在打印时都作为一页。 -->
