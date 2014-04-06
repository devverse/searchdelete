<!-- File: /app/View/Search/search1.ctp -->

<div> <!--container -->
<h1>Search Form <?php echo($network_name!='')?'- '.$network_name:'';?></h1>

<form action="<?php echo $client_url_name;?>/result" method="post">
    <input type="hidden" name="network_name" value="<?=$network_name?>"/>

    <div>
    Specialty
    <select name="specialtie_name">
        <option value="none">- All specialties -</option>
        <?php foreach($specialties as $specialty){
            if($specialty['Specialtie']['name']=='')
              continue;
            ?>
        	<option value="<?php echo $specialty['Specialtie']['name'];?>"><?php echo $specialty['Specialtie']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('specialtie_name') ; ?></span>
    </div>

    <div>
    Provider types
    <select name="providertype_name">
        <option value="none">- All Provider Types -</option>
        <?php foreach($providertypes as $providertype){ 
            if($providertype['Providertype']['name']=='')
              continue;
            ?>
            <option value="<?php echo $providertype['Providertype']['name'];?>"><?php echo $providertype['Providertype']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('providertype_name') ; ?></span>
    </div>


    <div>
    address
    <input value="" name="street address" placeholder="Address" type="text">
    city
    <input  value="" name="city" placeholder="city" type="text">
    state
    <select id="state" name="state" class="form-gender">
      <option value="None">No Preference</option>
      <option value="NY">NY</option>
    </select>
    Zip Code 
    <input id="textinput" name="zipcode" placeholder="Zip Code" type="text" value="">
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('zipcode') ; ?>
    </div>

    -OR-

    <div>
    Counties
    <select  name="countie_name" >
        <option value="none">- All Counties -</option>
        <?php foreach($counties as $countie){ ?>
        <option value="<?php echo $countie['Countie']['name'];?>"><?php echo $countie['Countie']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('countie_name') ; ?></span>
    </div>

    <div>
    Language
    <select id="specialty" name="language_name" >
        <option value="none">- All Languages -</option>
        <?php foreach($languages as $language){ ?>
            <option value="<?php echo $language['Language']['name'];?>"><?php echo $language['Lessanguage']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('language_name') ; ?></span>
    </div>

    <div>
    Gender
    <select id="state" name="gender">
      <option value="none">No Preference</option>
      <option value="M">M</option>
      <option value="F">F</option>
    </select>
    </div>

    <div>
    Accepts New
    <select id="state" name="acceptnew" >
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>

    <div>
    Accepts Medicare
    <select id="state" name="acceptmedicare" >
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>

    <div>
    Accepts Medicaid
    <select id="state" name="acceptmedicaid" >
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>

    <div>
    Handicap Accessable
    <select id="state" name="handicapaccess" >
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>

    <div>
    Insurances
    <select id="specialty" name="insurance_name">
        <option value="none">- All Insurances -</option> 
        <?php foreach($insurances as $insurance){ ?>
          <option value="<?php echo $insurance['Insurance']['name'];?>"><?php echo $insurance['Insurance']['name'];?></option>
        <?php } ?>
        </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('insurance_name') ; ?></span>
    </div>

    <div>
    Distance  
    <select id="distance" name="distance" class="form-control">
      <option value="0">Select a Distance</option>
      <option value="1">1 Miles</option>
      <option value="5">5 Miles</option>
      <option value="10">10 Miles</option>
      <option value="15">15 Miles</option>
      <option value="20">20 Miles</option>
    </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('distance') ; ?></span>
    </div>

    -Or-

    <div>
    <input id="textinput" name="distance_c" placeholder="Less than 100" value="" type="text">
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('distance_c') ; ?></span>
    </div>
      
    <div>
    firstname
    <input id="textinput"  value=""  name="firstname" placeholder="firstname" type="text">
    lastname
    <input id="textinput"  value=""  name="lastname" placeholder="lastname" type="text">
    </div>

    -OR-

    <div>
    practicename
<!--IF typed delete practice name-->
    <input id="textinput" value="" name="practicename" placeholder="practicenaem" type="text">
    </div>

    <button id="singlebutton" class="btn btn-primary">Search Provider</button>

    </form>

</div> <!-- /container -->