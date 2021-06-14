<!-- styling -->

<style>

.calendar-holiday {
    display: none;
    margin-top: 2em;
}

.list-holiday {
    display: block;
    margin-top: 2em;
}

.active-holiday-selection {
    background: #0d6efd !important;
    border: 1px solid #0d6efd;
}

.btn-selection:focus {
    background-color: none;
    outline: none;
    border: none;
}

.table-parent {
    max-height: 31em;
    height: auto;
    overflow: scroll;
}

.table-parent>.table>thead tr:nth-child(1) th,
#carousel-images-message-w>#table-images-w,
#carousel-images-message-m>#table-images-m {
    background: white;
    position: sticky;
    top: 0;
    z-index: 10;
}

.fas.fa-pen {
    color: #0d6efd;
}

.fas.fa-trash {
    color: red;
}

.edit-card {
    border: 1px solid red;
}

.btn-cancel-holiday {
    display: none;
}

.field-search-holiday {
    border-left: 1px solid #cccccc;
    border-bottom: 1px solid #cccccc;
    border-top: 1px solid #cccccc;
    border-right: none;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.btn-search-holiday {
    border-right: 1px solid #cccccc;
    border-bottom: 1px solid #cccccc;
    border-top: 1px solid #cccccc;
    border-left: 1px solid #cccccc;
    border-radius: 0;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    background: #e6e6e6;
}

.btn-times-search {
    border-top: 1px solid #cccccc;
    border-bottom: 1px solid #cccccc;
    border-radius: 0;
    color: red;
    display: none;
}

.btn-times-search:focus {
    background: #cccccc;
}

.btn-times-search>span {
    font-size: 0.8em;
}


</style>

<!-- styling -->

<!-- column form input -->

<div class="row mb-5" id="row-card-add">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
        <div class="card card-add-holiday" id="card-add-holiday">
            <div class="card-body">
                <h5 class="title-card-holiday">Data Hari Libur</h5>
                <form action="" id="form_holiday" class="mt-4">
                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <div class="input-group">
                            <input type="date" class="selected_date form-control" name="selected_date_start">
                            <input type="date" class="selected_date_finish form-control" name="selected_date_end">
                            <input type="hidden" class="holiday_id" name="holiday_id">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Alasan libur</label>
                        <div class="input-group select-holiday-group">
                            <input type="text" class="form-control col-8 holiday-reason" name="holiday_reason">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="save_holiday()" class="btn btn-sm btn-primary btn-add-holiday">Submit</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-cancel-holiday" onclick="cancel_edit_holiday()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
        <div class="button-selection">
            <div class="btn-group">
                <button class="btn btn-secondary active-holiday-selection btn-selection" id="list-selection" onclick="change_view('list')">List</button>
                <button class="btn btn-secondary btn-selection" id="calendar-selection" onclick="change_view('calendar')">Calendar</button>
            </div>
        </div>
        <div class="calendar-holiday">
            <div id="calendar-event"></div>
        </div>
        <div class="list-holiday">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-xl-8 col-lg-8 col-md-12 col-sm-12"></div>
                        <div class="col-12 col-xl-4 col-lg-4 col-md-12 col-sm-12">
                            <div class="search-area text-end mb-4">
                                <div class="input-group" style="align-items: center;">
                                    <input type="text" class="form-control field-search-holiday bg-light" placeholder="Ketik kata kunci">
                                    <button class="btn-times-search btn btn-light" onclick="clear_search_holiday()"><span>clear</span></button>
                                    <button class="btn btn-search-holiday" onclick="search_holiday(0)"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-parent">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Acara</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="target-list-holiday">
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination"></div>
                    <div class="pagination-search"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end column form input -->

<script>

    $(document).ready(function() {
        var option = $('select-option').length;

        var select = '<select class="form-control col-4" name="select-holiday-reason">' +
        '<option value=""></option>' +
        '</select>';
        
        if (option ==  0) {
            $('.holiday-reason').removeClass('col-8');
        } else {
            
        }

        // pagination 
        $('.pagination').on('click', 'a', function(e) {
            e.preventDefault();
            var pageno = $(this).attr('data-ci-pagination-page');
            $('.pagination').attr('data-active-page', pageno);
            get_holiday_list(pageno);
        });

        $('.pagination-search').on('click', 'a', function(e) {
            e.preventDefault();
            var pageno = $(this).attr('data-ci-pagination-page');
            var value = $('.field-search-holiday').val();
            $('.pagination-search').attr('data-active-page', pageno);
            search_holiday(pageno, value)
        });

        get_holiday_list(0)
    })

    function save_holiday() {
        var form_data = $('#form_holiday').serialize();
        
        $.ajax({
            type: 'post',
            data:  form_data,
            url:  '<?= site_url('holiday/save_holiday');?>',
            dataType:  'text',
            success: function(response) {

                //check current active page 
                var cur =  $('.active-holiday-selection').attr('id');

                if (cur = 'list-selection') {
                    get_holiday_list()
                } else {
                    get_holiday_calendar()
                }
                $('#form_holiday')[0].reset()
            }
        })
    }

    function post_edit_holiday(id) {
        var form_data = $('#form_holiday').serialize();
        
        $.ajax({
            type: 'post',
            data:  form_data,
            url:  '<?= site_url('holiday/post_edit_holiday');?>',
            dataType:  'text',
            success: function(response) {
                console.log(response)

                //clear styling card 
                cancel_edit_holiday()

                //change edit button

                //check current active page 
                var cur =  $('.active-holiday-selection').attr('id');
                var pageno = $('.pagination').attr('data-active-page');

                if (cur = 'list-selection') {
                    get_holiday_list(pageno)
                } else {
                    get_holiday_calendar()
                }
                $('#form_holiday')[0].reset()
            }
        })
    }

    function get_holiday_calendar() {
        $.ajax({
            type: 'post',
            url:  '<?= site_url('holiday/get_holiday');?>',
            dataType:  'json',
            success: function(response) {
                console.log(response.holiday[0])
                var calendarEl = document.getElementById('calendar-event')
                var calendarList = response.holiday;
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    initialDate: '2021-05-12',
                    events: response.holiday
                });
                calendar.render();
            }
        })
    }

    function get_holiday_list(page = '') {
        if (page == '') {
            var newPage = 0;
        } else {
            var newPage = page
        }

        $.ajax({
            type: 'get',
            url:  '<?= site_url();?>/holiday/get_holiday_list/' + newPage,
            dataType:  'json',
            success: function(response) {

                var tr = '';

                for (var i = 0; i < response.holiday.length; i++) {
                    tr += '<tr>' + 
                        '<td>'+ (i + 1) +'</td>' +
                        '<td>' + response.holiday[i].start + ' - ' + response.holiday[i].end + '</td>' +
                        '<td>'+ response.holiday[i].title +'</td>' +
                        '<td>' +
                        '<div class="btn-group"><a onclick="edit_holiday('+ response.holiday[i].id +')" href="#row-card-add" class="btn"><i class="fas fa-pen"></i></a><button onclick="delete_holiday('+ response.holiday[i].id +')" class="btn"><i class="fas fa-trash"></i></button></div>' +
                        '</td>' +
                        '</tr>';
                }
                
                $('.target-list-holiday').html(tr)
                $('.pagination').show()
                $('.pagination').html(response.pagination)
                $('.pagination-search').show()
            }
        })
    }

    function change_view(param) {
        if (param == 'calendar') {
            $('#list-selection').removeClass('active-holiday-selection');
            $('#calendar-selection').addClass('active-holiday-selection');
            $('.calendar-holiday').show();
            $('.list-holiday').hide();
            get_holiday_calendar()
        } else if (param == 'list') {
            $('#calendar-selection').removeClass('active-holiday-selection')
            $('#list-selection').addClass('active-holiday-selection');
            $('.list-holiday').show();
            $('.calendar-holiday').hide()
            get_holiday_list()
        }
    }

    function edit_holiday(id) {
        //get data
        $.ajax({
            type: 'post',
            data:  {
                id: id
            },
            url:  '<?= site_url('holiday/edit_holiday');?>',
            dataType:  'json',
            success: function(response) {
                console.log(response)

                $('.btn-cancel-holiday').show()
                $('.selected_date').val(response.start);
                $('.selected_date_finish').val(response.end);
                $('.holiday-reason').val(response.title)
                $('.title-card-holiday').text('Edit data');

                //custome box card 
                $('.card-add-holiday').addClass('edit-card');

                //custom button add 
                $('.btn-add-holiday').text('Edit')
                $('.btn-add-holiday').attr('onclick', 'post_edit_holiday('+ id +')')

                //add value 
                $('.holiday_id').val(id)

            }
        })
    }

    

    function delete_holiday(id) {
        var activePage = 
        $.ajax({
            type: 'post',
            data:  {
                id: id
            },
            url:  '<?= site_url('holiday/delete_holiday');?>',
            dataType:  'text',
            success: function(response) {

                //check current active page 
                var cur =  $('.pagination').attr('data-active-page');
                var pageno = $('.pagination').attr('data-active-page');

                if (response == 'success') {
                    get_holiday_list(pageno);
                    get_holiday_calendar();
                }
            }
        })
    }

    function cancel_edit_holiday() {
        //hide cancel button 
        $('.btn-cancel-holiday').hide();

        //reset form 
        $('#form_holiday')[0].reset()

        //remove style card 
        $('.card-add-holiday').removeClass('edit-card');

        //custom button add 
        $('.btn-add-holiday').text('Submit')
        $('.btn-add-holiday').attr('onclick', 'save_holiday()')
    }

    function search_holiday(page, val = '') {
        if (val == '') {
            var value = $('.field-search-holiday').val();
        } else {
            var value = val;
        }


        $.ajax({
            type: 'get',
            url:  '<?= site_url();?>/holiday/search_holiday/' + value + '/' + page,
            dataType:  'json',
            success: function(response) {
                var tr = '';

                for (var i = 0; i < response.holiday.length; i++) {
                    tr += '<tr>' + 
                        '<td>'+ (i + 1) +'</td>' +
                        '<td>' + response.holiday[i].start + ' - ' + response.holiday[i].end + '</td>' +
                        '<td>'+ response.holiday[i].title +'</td>' +
                        '<td>' +
                        '<div class="btn-group"><a onclick="edit_holiday('+ response.holiday[i].id +')" href="#row-card-add" class="btn"><i class="fas fa-pen"></i></a><button onclick="delete_holiday('+ response.holiday[i].id +')" class="btn"><i class="fas fa-trash"></i></button></div>' +
                        '</td>' +
                        '</tr>';
                }
                
                $('.target-list-holiday').html(tr)
                $('.pagination').hide()
                $('.pagination-search').html(response.pagination)

                $('.btn-times-search').show()
            }
        })
    }

    function clear_search_holiday() {
        get_holiday_list(0)

        $('.field-search-holiday').val('');
        $('.btn-times-search').hide()
    }   
</script>