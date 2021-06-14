
            </div>
            <!-- /div.container-fluid -->

        </section>
        <!-- /section.content -->

    </div>
    <!-- /div.content-wrapper -->

</div>
<!-- /div.wrapper -->

<a id="back-to-top" href="#" class="btn btn-primary back-to-top-left" role="button" aria-label="Scroll to top">
	<i class="fas fa-chevron-up text-light"></i>
</a>

<div id="modal-placehorder"></div>

<script>

	$(document).ready( function () {

		$('.select2').select2();
        
        $('.currency-rp').inputmask("numeric", {

            radixPoint: ".",
            groupSeparator: ",",
            digits: 0,
            autoGroup: true,
            prefix: '',
            rightAlign: true,
            allowMinus: false,
            oncleared: function () {
                self.value('');
            }

        });

	});

	function currency_rp() {
		
		$('.currency-rp').inputmask("numeric", {

            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            prefix: '',
            rightAlign: true,
            allowMinus: false,
            oncleared: function () {
                self.value('');
            }

        });
	}

    function number_format(x, decimal = false) {
        var dcml;
        if(decimal == false){
            dcml = 2;
        }else{
            dcml = decimal;
        }
        number = parseFloat(x).toFixed(dcml).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        //str = 'Rp ' + number;
        return number;
    }

    function number_format_0(x) {
        var dcml = 0;
        number = parseFloat(x).toFixed(dcml).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        //str = 'Rp ' + number;
        return number;
    }

    function number_format_2(x) {
        number = parseFloat(x).toFixed().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        str = 'Rp ' + number;
        return str;
    }

</script>

</body>
</html>