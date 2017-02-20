<html>
    <head>
        <link rel="stylesheet" type="text/css" href="{!! asset('css/styles/jqx.base.css') !!}"/>
        <script type="text/javascript" src="{!! asset('js/vendor/jquery.js') !!}"></script>
        <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxcore.js')!!}"></script>
        {!! HTML::script( asset('js/vendor/jqwidgets/jqxdata.js') ) !!}
        <script type="text/javascript" src="{!!asset('js/vendor/jqwidgets/jqxmenu.js')!!}"></script>
        <script src="{!! asset('/js/lib/c2menu.js') !!}"></script>
        <script>

            $(function() {
                var items = [
                {
                    id: "1",
                    text: "First Item",
                    parentid: "-1",
                    subMenuWidth: "250px"
                },
                {
                    id: "11",
                    text: "First sub-item",
                    parentid: "1"
                }
                ];

                var options = {
                    items: items,
                    callback: function(event) {
                        alert('Clicked ' + $(event.args).text());
                    },
                    height: 30,
                    width: "400px"
                };

                $('#testing').c2menu(options)
            });

        </script>
    </head>
    <body>
        <div id="testing"></div>
    </body>
</html>


