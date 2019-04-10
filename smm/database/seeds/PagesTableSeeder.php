<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('pages')->delete();
        \DB::table('pages')->insert([
            [
                'id' => 1,
                'slug' => 'faqs',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 2,
                'slug' => 'terms-and-conditions',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 3,
                'slug' => 'privacy-policy',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 4,
                'slug' => 'about-us',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 5,
                'slug' => 'contact-us',
                'content' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 6,
                'slug' => 'add-funds',
                'content' => '<h3><i>*** Important Information**</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 7,
                'slug' => 'new-order',
                'content' => '<h3><i>*** Important Information**</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 8,
                'slug' => 'mass-order',
                'content' => '<h3><i>*** Important Information**</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 9,
                'slug' => 'login-page',
                'content' => '<h3><i>👌*** Important Information**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 10,
                'slug' => 'register-page',
                'content' => '<h3><i>👌*** Important Information**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 11,
                'slug' => 'new-subscription-order',
                'content' => '<h3><i>👌*** Important Subscription Info **👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 12,
                'slug' => 'bank-account-page',
                'content' => '<h3><i>👌*** Bank Account Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 13,
                'slug' => 'instamojo-page',
                'content' => '<h3><i>👌*** Instamojo Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 14,
                'slug' => 'paytm-page',
                'content' => '<h3><i>👌*** Paytm Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">
<meta name="keywords" content="A Keyword">'
            ],
            [
                'id' => 15,
                'slug' => 'paywant-page',
                'content' => '<h3><i>👌*** Paywant Info**👌</i></h3><p><i><u><b></b></u></i><u><b>You can add important information on different pages for your customers</b></u><i><br></i></p><p><i>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. </i><br></p><p></p>',
                'meta_tags' => '<meta name="description" content="A description">'
            ],
        ]);
    }
}
