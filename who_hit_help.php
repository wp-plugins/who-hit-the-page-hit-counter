
<div class="wrap">
	<h2>Who Hit The Page - Hit Counter.</h2>
    
    <p>
    	See who visited your website below. The hits per page are shown on the first table, and the visitor's IP addresses are shown on the last table.
    </p>
    
    <p>
    	Place the following shortcode snippet in any page or post you wish visitors counted on. 
    </p>
    
    <p>
    	<code>[whohit]-Page name or identifier-[/whohit]</code> - please remember to replace the `<code>-Page name or identifier-</code>` with the name of the page you placed the shortcode on, if you like you can put in anything you want to use as an identifier for the page.
    </p>
    
    <p>
    	For example: On our <a href="http://3pxwebstudios.co.nf/about-us">about us page</a> we placed <code>[whohit]About Us[/whohit]</code> and on our <a href="http://3pxwebstudios.co.nf/web-hosting">web hosting</a> page we placed <code>[whohit]Web Hosting[/whohit]</code>. Please note that what you put between [whohit] and [/whohit] doesn\'t need to be the same as the page name - that means; for our <a href="http://3pxwebstudios.co.nf/web-design-and-development">website design and development page</a> we can use <code>[whohit]Development[whohit]</code> instead of the whole <code>[whohit]website design and development page[whohit]</code> string, its completely up to you what you put as long as you will be able to see it on your admin what page has how many visits.
    </p>
    <p>
    	Please make sure you place the shortcode <code>[whohit]..[/whohit]</code> only once in a page or post, if you place it twice, that page will be counted twice and thats not what you want. If you don\'t put anything between the inner square brackets of the shortcode, like so:<code>[whohit][/whohit]</code>, then you will have an unknown page appering with a count on the hits table and you will not know what page that is on your website.
    </p>
</div>  

<div class="wrap">
    <h2> Help and support</h2>
    <ul class="help">
        <li> 
            <h5>Please Rate Who Hit The Page on WordPress</h5>
            <p>
            Please do not forget to rate this plugin if you love it. 
            <a href="http://plugins.wordpress.org/who-hit-the-page-hit-counter">Rate this plugin now.</a> 
            Rating this plugin will help other people like you to find this plugin because on wordpress plugins are sorted by rating, so rate it high and give it a fair review to help others find it.
            </p>
            <p><a href="http://plugins.wordpress.org/who-hit-the-page-hit-counter">Rate this plugin now.</a></p>
            <strong>Optional: </strong>	Please link to our website if you like our plugin, we really appreciate your kind gesture. Visit our website at <?php echo whtp_link_bank(); ?>
            <br />
            <br />
            Please copy and paste this: <code>[whohit]Page name or identifier[/whohit]</code> on any page or post in visual view to display the link shown above.<br /><br />
            Or you can copy and paste the following link on your pages, posts or theme files to display a link to our website
            <textarea readonly="readonly"><?php echo whtp_link_bank(); ?></textarea>
            <br />
            
        </li>
        
        <!--  author contact details -->
        <li>	
            <ul class="author">
                <li><b>Author's website</b></li>
                <li>
                    <a href="http://3pxwebstudios.co.nf" title="Worpress plugins author" target="_blank">
                        3pxwebstudios.co.nf
                    </a>
                </li>
                
                <li><b>Plugin's documentation</b></li>
                <li>
                    <a href="http://3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter" title="Multi Purpose Mail Form wordpress plugin" target="_blank">
                        http://3pxwebstudios.co.nf/wordpress-resources/who-hit-the-page-hit-counter
                    </a>
                </li>
                
                <li><b>Report bugs/ request features</b></li>
                <li>
                    <a href="mailto:3pxwebstudios@gmail.com" title="email address to report plugin's bugs or errors" target="_blank">
                        3pxwebstudios@gmail.com
                    </a>
                </li>
                
                <li><b>Contact phone number</b><br /><small>(Only from Monday to Friday )</small></li>
                <li>
                    27 76 706 4015 (ZA)
                </li>
            </ul>
        </li>
        <!-- subscription form -->
        <li>
            <?php
                if(isset($_POST['whtpsubscr']) && $_POST['whtpsubscr'] == "y"){
                    whtp_admin_message_sender();
                }
            ?>
            <?php				
           		whtp_signup_form();
            ?>               
        </li>
    </ul>
</div>

<div class="wrap">
	<h1>Credits Where They Belong</h1>
    <div>
    	This product includes GeoLite2 data created by MaxMind, available from
<a href="http://www.maxmind.com">http://www.maxmind.com</a>.
    </div>
</div>