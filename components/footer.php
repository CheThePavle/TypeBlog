<footer id="footer" class="footer">
    <div class="footer-bottom">
        <div class="container">
            <div class="row" style="background-color: lightslategrey; border-radius: 4px; padding: 10px">
                <p style="color: white" class="pull-left">Copyright ©2021</p>
                <p style="color: white" class="pull-right">Чикуров Павел</p>
            </div>
        </div>
    </div>
</footer>
</div>

<div class="preloader">
    <div class="preloader__row">
        <div class="preloader__item"></div>
        <div class="preloader__item"></div>
    </div>
</div>

<script src="https://bootstraptema.ru/plugins/2015/b-v3-3-6/bootstrap.min.js"></script>
<script src="https://bootstraptema.ru/snippets/menu/2016/slidemenu/slidemenu.js"></script>
<script>
    window.onload = function () {
        $("body").attr('class', 'loaded_hiding');
        window.setTimeout(function () {
            $("body").attr('class', 'loaded');
            $("body").removeAttr('loaded_hiding');
        }, 500);
    }
</script>
</body>
</html>