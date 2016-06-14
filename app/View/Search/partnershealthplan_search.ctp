<?php echo $this->Session->flash(); ?>
<div class="container" id="providersearch"> <!--container -->
<div class="row">
	<div class="col-md-12">
	
	<h2><span class="glyphicon glyphicon-search"></span> Search Form
	</div>
</div>


<script type="text/javascript">
$(function(){
    var current_search = 'none';
        $('form').on('click','#singlebutton',function()
        {
            var flashmsg = false;
            if(current_search=='name' && $('select[name=providertype_name]').val()=='none' && $('#pracitcename').val()=='' && $('select[name=specialtie_name]').val()=='none')
                flashmsg = 'Please type in a practice name or select a provider type.'
           
            if(current_search=='location'&& $('input[name=street_address]').val()==''&& $('input[name=city]').val()==''&& $('input[name=zipcode]').val()=='')
                flashmsg = 'Please type in an address , city , zipcode.'
     
            if(current_search=='location'&& $('input[name=street_address]').val()!=''&& ($('input[name=city]').val()==''&& $('input[name=zipcode]').val()==''))
                flashmsg = 'Please type in an city or zipcode.'            

 
            if(current_search=='county'&& $('select[name=countie_name]').val()=='none'&& $('select[name=specialtie_name]').val()=='none'&& $('select[name=providertype_name]').val()=='none')
                flashmsg = 'Please select a county or provider type first.'

            if(flashmsg)
            {
                $('#flash-msg').html('<span class="field-error">'+flashmsg+'</span>');
                return false;
            }
            
        });
});
</script>

<form action="/search/<?php echo $client_url_name;?>/result" method="post" class="form-horizontal">
    <input name="search_user" type="hidden" value="partnerhealthplan">
    
    <div class="form-group searchtype-name" >
        <label class="col-sm-2 control-label">Practice Name</label>
        <!--IF typed delete practice name-->
        <div class="col-sm-6">
        <input id="pracitcename" value="" name="practicename" placeholder="Practice Name" type="text" class="form-control">
        </div>
    </div>
    <div class="form-group searchtype-name" >
        <label class="col-sm-2 control-label">Provider Name</label>
        <!--IF typed delete practice name-->
        <div class="col-sm-6">
        <input id="firstname" value="" name="firstname" placeholder="First Name" type="text" class="form-control">
        <input id="lastname" value="" name="lastname" placeholder="Last Name" type="text" class="form-control">
        </div>
    </div>

    <div class="form-group">
     <div class="col-sm-offset-2 col-sm-10">--- or ---</div>
</div>

<div class="searchtype-location" ><!--Beginingof Adresses-->
    <div class="form-group">
      <label class="col-sm-2 control-label">Address</label>
      <div class="col-sm-8">
      <input value="" name="street_address" placeholder="Address" type="text" class="form-control">
      </div>
    </div>
    
    
    <div class="form-group">
     <label class="col-sm-2 control-label">City</label>
     <div class="col-sm-2">
    <input value="" name="city" placeholder="City" type="text" class="form-control">
    </div>
    <div class="col-sm-1">
    <label class="control-label">State</label>
    </div>
    <div class="col-sm-2">

    <select id="state" name="state" class="form-control">
        <option value="None" selected="selected">Select a State</option> 
        <option value="AL">Alabama</option> 
        <option value="AK">Alaska</option> 
        <option value="AZ">Arizona</option> 
        <option value="AR">Arkansas</option> 
        <option value="CA">California</option> 
        <option value="CO">Colorado</option> 
        <option value="CT">Connecticut</option> 
        <option value="DE">Delaware</option> 
        <option value="DC">District Of Columbia</option> 
        <option value="FL">Florida</option> 
        <option value="GA">Georgia</option> 
        <option value="HI">Hawaii</option> 
        <option value="ID">Idaho</option> 
        <option value="IL">Illinois</option> 
        <option value="IN">Indiana</option> 
        <option value="IA">Iowa</option> 
        <option value="KS">Kansas</option> 
        <option value="KY">Kentucky</option> 
        <option value="LA">Louisiana</option> 
        <option value="ME">Maine</option> 
        <option value="MD">Maryland</option> 
        <option value="MA">Massachusetts</option> 
        <option value="MI">Michigan</option> 
        <option value="MN">Minnesota</option> 
        <option value="MS">Mississippi</option> 
        <option value="MO">Missouri</option> 
        <option value="MT">Montana</option> 
        <option value="NE">Nebraska</option> 
        <option value="NV">Nevada</option> 
        <option value="NH">New Hampshire</option> 
        <option value="NJ">New Jersey</option> 
        <option value="NM">New Mexico</option> 
        <option value="NY">New York</option> 
        <option value="NC">North Carolina</option> 
        <option value="ND">North Dakota</option> 
        <option value="OH">Ohio</option> 
        <option value="OK">Oklahoma</option> 
        <option value="OR">Oregon</option> 
        <option value="PA">Pennsylvania</option> 
        <option value="RI">Rhode Island</option> 
        <option value="SC">South Carolina</option> 
        <option value="SD">South Dakota</option> 
        <option value="TN">Tennessee</option> 
        <option value="TX">Texas</option> 
        <option value="UT">Utah</option> 
        <option value="VT">Vermont</option> 
        <option value="VA">Virginia</option> 
        <option value="WA">Washington</option> 
        <option value="WV">West Virginia</option> 
        <option value="WI">Wisconsin</option> 
        <option value="WY">Wyoming</option>
    </select>

    </div>
    <div class="col-sm-1">
    <label class="control-label"><span class="field-error">*</span> ZIP</label>
    </div>
    <div class="col-sm-2">
    <input id="zipcode" name="zipcode" placeholder="Zip Code" type="text" value="" class="form-control">
    <span class="field-error"><?php echo $this->Session->flash('zipcode') ; ?></span>
    </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Distance </label> 
        <div class="col-sm-6">
        <select id="distance" name="distance" class="form-control">
         <!--<option value="0">Select a Distance</option>-->
          <option value="1">1 Miles</option>
          <option value="5">5 Miles</option>
          <option value="10">10 Miles</option>
          <option value="15">15 Miles</option>
          <option value="20">25 Miles</option>
          <option value="50">50 Miles</option>
          <option value="75">75 Miles</option>
          <option value="100">100 Miles</option>
        </select>
        <span class="field-error"><?php echo $this->Session->flash('distance') ; ?></span>
        </div>
    </div>
</div><!--End of Addresses-->

    <div class="form-group" >
     <div class="col-sm-offset-2 col-sm-10">--- or ---</div>
    </div>

    <div class="form-group searchtype-county" >
    <label class="col-sm-2 control-label"><span class="field-error">*</span> Counties of Service</label>
    <div class="col-sm-8">
        <select  name="countie_name" class="form-control">
        <option value="none">All Counties</option>
    <option value="Bronx"> Bronx </option>
    <option value="Kings"> Kings </option>
    <option value="Nassau"> Nassau </option>
    <option value="New York"> New York </option>
    <option value="Queens"> Queens </option>
    <option value="Rockland"> Rockland </option>
    <option value="Richmond"> Richmond </option> 
    <option value="Suffolk"> Suffolk </option>
    <option value="Westchester"> Westchester </option>    
</select>
    <span class="field-error"><?php echo $this->Session->flash('countie_name') ; ?></span>
    </div>

    </div>

    <div class="form-group" style="display:none">
    <div class="col-sm-offset-2 col-sm-6"><input id="radiussearch" name="distance_c" placeholder="Less than 100" value="" type="text" class="form-control">
    <span class="field-error"><?php echo $this->Session->flash('distance_c') ; ?></span>
    </div>
    </div>

    <div class="filter-by" ><!--filter by-->
    <div class="form-group">
     <div class="col-sm-offset-2 col-sm-10">Filter By:</div>
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Provider Type</label>
    <div class="col-sm-6">
    <select name="providertype_name" class="form-control">
            <option value="none">- All Provider Types -</option>
        <?php foreach($providertypes as $providertype){ 
            
            ?>
            <option data-id="<?php echo $providertype['Providertype']['id']; ?>" value="<?php echo $providertype['Providertype']['name'];?>"><?php echo $providertype['Providertype']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error"><?php echo $this->Session->flash('providertype_name') ; ?></span>
    </div>
    </div>

     <div class="form-group">
        <label class="col-sm-2 control-label">Specialty</label>
        <div class="col-sm-6">
        <select name="specialtie_name" class="form-control">
        <option value="none">- All Specialties -</option>
        <?php foreach($specialties as $specialty){
            if($specialty['Specialtie']['name']=='')
              continue;
            ?>
            <option data-parent="<?php echo $specialty['Specialtie']['parent_id']; ?>" value="<?php echo $specialty['Specialtie']['name'];?>"><?php echo $specialty['Specialtie']['name'];?></option>
        <?php } ?>
        </select>
    <span class="field-error"><?php echo $this->Session->flash('specialtie_name') ; ?></span>
        </div>
    </div>

    <div class="form-group" >
    <label class="col-sm-2 control-label">Language</label>
    <div class="col-sm-8">
    <select id="lauguage" name="language_name" class="form-control">
        <option value="none">All Languages</option>
        <?php foreach($languages as $language){ ?>
            <option value="<?php echo $language['Language']['name'];?>"><?php echo $language['Language']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error"><?php echo $this->Session->flash('language_name') ; ?></span>
    </div>
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Gender</label>
    <div class="col-sm-8">
    <select id="gender" name="gender" class="form-control">
      <option value="none">No Preference</option>
      <option value="M">M</option>
      <option value="F">F</option>
    </select>
    </div>
    </div>

<!--     <div class="form-group"  >
    <label class="col-sm-2 control-label">Accepts New</label>
    <div class="col-sm-8">
    <select id="acceptnew" name="acceptnew" class="form-control">
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>
    </div> -->
<!-- 
    <div class="form-group"  >
    <label class="col-sm-2 control-label">Accepts Medicare</label>
    <div class="col-sm-8">
    <select id="acceptmediarestate" name="acceptmedicare" class="form-control">
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>
    </div> -->

<!--     <div class="form-group">
    <label class="col-sm-2 control-label">Handicap Accessible</label>
    <div class="col-sm-8">
    <select id="handicapaccess" name="handicapaccess" class="form-control">
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>
    </div> -->

<!--     <div class="form-group">
    <label class="col-sm-2 control-label">Insurances</label>
    <div class="col-sm-8">
    <select id="specialty" name="insurance_name" class="form-control">
            <option value="none">All Insurances</option> 
        <?php foreach($insurances as $insurance){ ?>
          <option value="<?php echo $insurance['Insurance']['name'];?>"><?php echo $insurance['Insurance']['name'];?></option>
        <?php } ?>
        </select>
    <span class="field-error"><?php echo $this->Session->flash('insurance_name') ; ?></span>
    </div>
    </div> -->
    
    <!--  <div class="form-group">
    <label class="col-sm-2 control-label">First Name</label>
    <div class="col-sm-6">
    <input value=""  name="firstname" placeholder="firstname" type="text" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label class="col-sm-2 control-label">Last Name</label>
    <div class="col-sm-6">
    <input value=""  name="lastname" placeholder="lastname" type="text" class="form-control">
    </div>
    
    </div>
    -->
    <div class="col-sm-offset-2"><span class="field-error">*</span> Required Fields</p>

    <button id="singlebutton" type="submit" class="btn btn-primary btn-lg btn-custom">Search Provider</button>
<br/>
<br/>
<div id="flash-msg"><!--Error Message Section-->
    <span class="field-error"><?php echo $this->Session->flash('zipcode') ; ?></span>
    <span class="field-error"><?php echo $this->Session->flash('distance') ; ?></span>
    <span class="field-error"><?php echo $this->Session->flash('countie_name') ; ?></span>
     <span class="field-error"><?php echo $this->Session->flash('distance_c') ; ?></span>
    <span class="field-error"><?php echo $this->Session->flash('specialtie_name') ; ?></span>
    <span class="field-error"><?php echo $this->Session->flash('providertype_name') ; ?></span>
    <span class="field-error"><?php echo $this->Session->flash('language_name') ; ?></span>
    <span class="field-error"><?php echo $this->Session->flash('insurance_name') ; ?></span>
</div>
</div><!--filterby-->
    </form>
</div> <!-- /container -->
