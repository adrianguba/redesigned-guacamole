<?php get_header();?>
<div class="container main-content">
    <div class="row">
        <div class="col-md-12 form-header">
            <div>
                <img src="<?php echo get_template_directory_uri().'/img/osomstudio-logo2.svg';?>" alt="">
                <h1>Wypełnij formularz</h1>
            </div>

        </div>
    </div>
    <form id="submission-form">
        <div class="row form-content">
                <div class="col-md-4">
                    <label>First name:</label>
                    <input type="text" name="first_name" required/>
                    <label>Last name:</label>
                    <input type="text" name="last_name" required/>
                    <label>Login:</label>
                    <input type="text" name="login" required/>
                </div>
                <div class="col-md-4">
                    <label>User email:</label>
                    <input type="email" name="email" required/>
                    <label>City:</label>
                    <select name="city" required>
                        <option value="katowice">Katowice</option>
                        <option value="lodz">Łódź</option>
                        <option value="warszawa">Warszawa</option>
                    </select>
                    <input type="submit" value="Send"/>
                </div>
                <div class="col-md-4">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores culpa debitis dolorem et excepturi illum iste iusto, magnam minima mollitia nesciunt officia perspiciatis praesentium reprehenderit sapiente sit, temporibus unde vitae!</p>
                    <label class="agreement">
                        <input type="checkbox"/>
                        I agree with Terms & Conditions</label>
                </div>
        </div>
    </form>
</div>
<?php get_footer();?>
