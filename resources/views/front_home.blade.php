<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <!-- Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<style type="text/css">
    h2{
        margin:80px auto;
    }    
</style>
    </head>
    <body class="antialiased">
        <div class="container  mt-5 mb-5">
            <h1>Complete your booking.
            </h1>
            <h2>
                Total Bill: 
                <div id="result" class="container"></div>
            </h2>
            @if($errors->any())
                {{ implode('', $errors->all('<div>:message</div>')) }}
            @endif
            
             <form   role="form"  action="{{route('check_orders.store')}}"  method="post" 
                            class="require-validation"
                            data-cc-on-file="false"
                            data-stripe-publishable-key="pk_test_H0n8RftpV3rgITLNU4HpFqMs"
                            id="payment-form">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="inputEmail4">First Name</label>
                    <input type="text" class="form-control" id="inputEmail4" placeholder="Enter First" name="first_name">
                    </div>
                    <div class="form-group col-md-6">
                    <label for="">Last Name</label>
                    <input type="text" class="form-control" id="inputPassword4" placeholder="Last Name" name="last_name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="inputEmail4">Email</label>
                    <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email">
                    </div>
                    <div class="form-group col-md-6">
                    <label for="inputPassword4">Phone Number</label>
                    <input type="number" class="form-control" id="inputPassword4" placeholder="Phone Number" name="phone_number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                    <label for="inputAddress">Address</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="DW Main St" name="address">
                    </div>
                    <div class="form-group col-md-4">
                    <label for="inputAddress">Apt/Suit #</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="1234 " name="apt_suite_number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="inputCity">City</label>
                    <input type="text" class="form-control" id="inputCity" name="city">
                    </div>
                    <div class="form-group col-md-4">
                    <label for="inputState">State</label>
                    <select id="inputState" class="form-control" name="state">
                        <option selected>Choose...</option>
                        <option>West</option>
                        <option>North</option>
                    </select>
                    </div>
                    <div class="form-group col-md-2">
                    <label for="inputZip">Zip</label>
                    <input type="text" class="form-control" id="inputZip" name="zipcode">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label >Bed Room</label>
                    <select class="form-control"  name="room_id" id="room_id">
                        <option value="">--Select Room--</option>
                        @foreach ($rooms as $room)
                        <option value="{{$room->id}}"  data-price="{{$room->price}}">{{$room->title}}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="form-group col-md-6">
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Bath Room</label>
                        <select class="form-control"  name="bathroom_id" id="bathroom_id" >
                            <option value="">--Select Bathroom--</option>
                            @foreach($bathrooms as $bathroom)
                            <option value="{{$bathroom->id}}" data-price="{{$bathroom->price}}">{{$bathroom->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                </div>
              
                <h6>Extra Services</h6>
                <div class="form-row">
                    @foreach($services as $service)
                        <div class="form-group col-md-3">
                            <div id="services">
                                <input class="form-check-input" type="checkbox" value="{{$service->id}}" name="services[]"  data-price="{{ $service->price }}">
                                <label class="form-check-label" for="flexCheckDefault">
                                {{$service->title}} &nbsp ${{$service->price}}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <br>
                 <h6>Cleaning Type</h6>
                    <div class="form-row">
                        @foreach($cleaning_types as $cleaning_type)
                        <div class="form-group col-md-4">
                            <div class="form-check">
                                <div id="checker">
                                    <input type="checkbox" class="form-check-input" name="cleaning_types[]" id="add-dj" value="{{$cleaning_type->id}}" data-price="{{ $cleaning_type->price }}">
                                </div>
                                <label class="form-check-label" for="flexCheckDefault">
                                {{$cleaning_type->title}}&nbsp ${{$cleaning_type->price}}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                <div class="form-row">
                    <div class="form-group col-md-9">
                        <label for="exampleFormControlSelect1">Discount</label>
                        <select class="form-control" id="exampleFormControlSelect1"  name="discount_id" id="discount_id">
                            <option value="">--Select Discount--</option>
                            @foreach ($discounts as $discount)
                            <option value="{{$discount->id}}"  data-price="{{$discount->price}}">{{$discount->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="exampleFormControlSelect1">TimeSlots</label>
                        <select class="form-control"   name="time_slot_id">
                            <option value="">--Select TimeSlot--</option>
                            @foreach($time_slots as $time_slot)
                            <option value="{{$time_slot->id}}">{{$time_slot->time_slot}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputZip">Date</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                </div>
                    
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="exampleFormControlSelect1">Have you been in contact with anyone that has tested positive for COVID-19 in the last 30 days?</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="contact_with_covid_person">
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                    <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name on Card</label> <input
                                    class='form-control' size='4' type='text'>
                            </div>
                        </div>
    
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group card required'>
                                <label class='control-label'>Card Number</label> <input
                                    autocomplete='off' class='form-control card-number' size='20'
                                    type='text'>
                            </div>
                        </div>
    
                        <div class='form-row '>
                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                <label class='control-label'>CVC</label> <input autocomplete='off'
                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Month</label> <input
                                    class='form-control card-expiry-month' placeholder='MM' size='2'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Year</label> <input
                                    class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                    type='text'>
                            </div>
                        </div>
    
                        <div class='form-row '>
                            <div class='col-md-12 error form-group hide'>
                                <div class='alert-danger alert'>Please correct the errors and try
                                    again.</div>
                            </div>
                        </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="exampleFormControlSelect1">Total Bill</label>
                        <input
                            class='form-control' id="totalbill" name="totalbill">
                    </div>
                </div>
                  
    
                   
                    <div class="col-md-12 text-center mb-5">
                    <button type="submit" class="btn btn-primary">Book Now</button>
                    </div>
            </form> 
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            function validate() {
                var sum = 0;
                sum += +$('#bathroom_id option:selected').data('price') || 0;
                sum += +$('#room_id option:selected').data('price') || 0;
                sum += +$('#discount_id option:selected').data('price') || 0;
                
                //loop through checked checkbox
                $('#checker input:checked').each(function() {
                sum += +$(this).data('price') //get dataprice add in sum
                })
                $('#services input:checked').each(function() {
                sum += +$(this).data('price') //get dataprice add in sum
                })
                
                var bill = sum === 0 ? '0.00' : sum ;
                
                    document.querySelector('input[name="totalbill"]').value = bill ?? ''; 
                $('#result').html(bill);
                
            }
            validate();
        
            $('#bathroom_id, #discount_id,input, #room_id, #checker input').on('change', function() {
                validate();
            });
        
            });
    </script>
   
    
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        
    <script type="text/javascript">
    
        $(function() {
        
            /*------------------------------------------
            --------------------------------------------
            Stripe Payment Code
            --------------------------------------------
            --------------------------------------------*/
            
            var $form = $(".require-validation");
            
            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                                'input[type=text]', 'input[type=file]',
                                'textarea'].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
                $errorMessage.addClass('hide');
            
                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
                });
            
                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
                }
            
            });
            
            /*------------------------------------------
            --------------------------------------------
            Stripe Response Handler
            --------------------------------------------
            --------------------------------------------*/
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];
                        
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }
            
        });
    </script>
</html>