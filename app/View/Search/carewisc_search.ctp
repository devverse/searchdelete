<!-- File: /app/View/Search/search1.ctp -->

<div class="container"> <!--container -->
<h2>Search Form <?php echo($network_name!='')?'- '.$network_name:'';?></h2>

<form action="<?php echo $client_url_name;?>/result" method="post" class="form-horizontal">
    <input type="hidden" name="network_name" value="<?=$network_name?>"/>

    <div class="form-group">
    	<label class="col-sm-2 control-label">Specialty</label>
    	<div class="col-sm-6">
    	<select name="specialtie_name" class="form-control">
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
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Provider Types</label>
    <div class="col-sm-6">
    <select name="providertype_name" class="form-control">
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
    </div>

	
    <div class="form-group">
    	<label class="col-sm-2 control-label">Address</label>
    	<div class="col-sm-8">
    	<input value="" name="street address" placeholder="Address" type="text" class="form-control">
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
      <option value="None">States</option>
      <option value="NY">NY</option>
    </select>
    </div>
    <div class="col-sm-1">
    <label class="control-label">Zip</label>
    </div>
    <div class="col-sm-2">
    <input id="textinput" name="zipcode" placeholder="Zip Code" type="text" value="" class="form-control">
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('zipcode') ; ?>
    </div>
    </div>
<div class="form-group">
     <div class="col-sm-offset-2 col-sm-10">--- or ---</div>
</div>
    <div class="form-group">
    <label class="col-sm-2 control-label">Counties</label>
    <div class="col-sm-8">
        <select  name="countie_name" class="form-control">
        <option value="none">All Counties</option>
        <?php foreach($counties as $countie){ ?>
        <option value="<?php echo $countie['Countie']['name'];?>"><?php echo $countie['Countie']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('countie_name') ; ?></span>
    </div>

    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Language</label>
    <div class="col-sm-8">
    <select id="specialty" name="language_name" class="form-control">
        <option value="none">All Languages</option>
        <?php foreach($languages as $language){ ?>
            <option value="<?php echo $language['Language']['name'];?>"><?php echo $language['Lessanguage']['name'];?></option>
        <?php } ?>
    </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('language_name') ; ?></span>
    </div>
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Gender</label>
    <div class="col-sm-8">
    <select id="state" name="gender" class="form-control">
      <option value="none">No Preference</option>
      <option value="M">M</option>
      <option value="F">F</option>
    </select>
    </div>
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Accepts New</label>
    <div class="col-sm-8">
    <select id="state" name="acceptnew" class="form-control">
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Accepts Medicare</label>
    <div class="col-sm-8">
    <select id="state" name="acceptmedicare" class="form-control">
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>
    </div>


    <div class="form-group">
    <label class="col-sm-2 control-label">Handicap Accessible</label>
    <div class="col-sm-8">
    <select id="state" name="handicapaccess" class="form-control">
      <option value="none">No Preference</option>
      <option value="Y">Y</option>
      <option value="N">N</option>
    </select>
    </div>
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Insurances</label>
    <div class="col-sm-8">
    <select id="specialty" name="insurance_name" class="form-control">
            <option value="none">All Insurances</option> 
        <?php foreach($insurances as $insurance){ ?>
          <option value="<?php echo $insurance['Insurance']['name'];?>"><?php echo $insurance['Insurance']['name'];?></option>
        <?php } ?>
        </select>
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('insurance_name') ; ?></span>
    </div>
    </div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Distance </label> 
    <div class="col-sm-6">
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
    </div>

   <div class="form-group">
     <div class="col-sm-offset-2 col-sm-10">--- or ---</div>
</div>

    <div class="form-group">
    <div class="col-sm-offset-2 col-sm-6"><input id="textinput" name="distance_c" placeholder="Less than 100" value="" type="text" class="form-control">
    <span class="field-error" style="color:red;"><?php echo $this->Session->flash('distance_c') ; ?></span>
    </div>
    </div>
    
      <div class="form-group">
    <label class="col-sm-2 control-label">First Name</label>
    <div class="col-sm-6">
    <input id="textinput"  value=""  name="firstname" placeholder="firstname" type="text" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label class="col-sm-2 control-label">Last Name</label>
    <div class="col-sm-6">
    <input id="textinput"  value=""  name="lastname" placeholder="lastname" type="text" class="form-control">
    </div>
    
    </div>
    
    <div class="form-group">
     <div class="col-sm-offset-2 col-sm-10">--- or ---</div>
</div>

    <div class="form-group">
    <label class="col-sm-2 control-label">Practice Name</label>
<!--IF typed delete practice name-->
<div class="col-sm-6">
    <input id="textinput" value="" name="practicename" placeholder="Practice Name" class="form-control" type="text" class="form-control">
    </div>
    </div>

    <button id="singlebutton" type="submit" class="col-sm-offset-2 btn btn-default">Search Provider</button>

    </form>

</div> <!-- /container -->