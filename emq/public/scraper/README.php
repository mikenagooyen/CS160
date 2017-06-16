<?php
/*
*	Scraper Tested and Approved with: FIREFOX, CHROME
*	
*	
*	Step 1A: Make a copy of scraper/scraper_code.php
*			Rename it to index.php
*
*	Step 1B: Open up the index.php
*			Redefine the const CATEGORY and SAVE2FILE.
*			CATEGORY = the category provided below
*			SAVE2FILE = your_name.txt
*
*			Save and launch the scraper by visiting.
*			http://localhost/emq/public/scraper
*			Confirm that you see the correct CATEGORY and SAVE2FILE
*
*****************************************************
*
*	Step 2A: Visit the URL provided below and find a product page that you wish to scrape.
*	
*	Step 2B: Enter the product URL into the scraper and click generate.
*			A properly formatted entry will be inserted into your text file and an image name will be provided.
*			Confirm the data appears correctly.
*
*			Copy the image title to your clipboard.
*
*	Step 2C: On the Amazon Product Page.
*			Click on the Product Image until a pop up window of the product appears.
*			You should now see a high resolution image of the product.
*
*			Right click on the image and hit "Save Image As".
*
*			Paste the provided image name that was generated.
*			Save the image into the folder emq/public/scraper/your_name/
*	
*		REPEAT UNTIL YOU HAVE 36 ENTRIES PER CATEGORY
*
*	STEP 3A: Delete index.php once your finished and push your collected results.
*
*	I'll combine all our data into PRODUCT_SEEDER once we have all finished collecting everything.
*/


Frederick{
	Category => "Bluetooth & Wireless Speakers"
	https://www.amazon.com/Multiroom-Digital-Music-Systems/b/ref=sd_allcat_bluetoothwireless?ie=UTF8&node=322215011
}


Emmanuel{
	Category => "Computer Parts & Components"
	https://www.amazon.com/PC-Parts-Components/b/ref=sd_allcat_components?ie=UTF8&node=193870011
}

Ha{
	Category => "Office Electronics"
	https://www.amazon.com/b/ref=s9_acss_bw_cg_E161211A_2d1?node=172574&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-3&pf_rd_r=J6R78RZTSM3SWYR9F23R&pf_rd_t=101&pf_rd_p=2346e902-e243-418c-b8af-cad6bb3a6488&pf_rd_i=172282
}

David{
	Category => "TVs & Accessories"
	https://www.amazon.com/b/ref=s9_acss_bw_cg_E161211A_2b1?node=1266092011&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-3&pf_rd_r=J6R78RZTSM3SWYR9F23R&pf_rd_t=101&pf_rd_p=2346e902-e243-418c-b8af-cad6bb3a6488&pf_rd_i=172282
}

Mike{
	Category => "Video Games"
	https://www.amazon.com/computer-video-games-hardware-accessories/b/ref=sd_allcat_cvg_ce?ie=UTF8&node=468642
}





EXTRA CATEGORIES IF YOU FEEL LIKE IT. PLEASE INFORM OTHERS IF YOU END UP CLAIMING AN EXTRA CATEGORY

Extra CAMERA_&_VIDEO{
	Category => "Camera & Video"
	https://www.amazon.com/b/ref=s9_acss_bw_cg_E161211A_2e1?ie=UTF8&node=502394&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-3&pf_rd_r=J6R78RZTSM3SWYR9F23R&pf_rd_t=101&pf_rd_p=2346e902-e243-418c-b8af-cad6bb3a6488&pf_rd_i=172282
}

Extra CAR_ELECTRONICS{
	Category => "Car Electronics"
	https://www.amazon.com/b/ref=s9_acss_bw_cg_E161211A_2f1?node=1077068&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-3&pf_rd_r=J6R78RZTSM3SWYR9F23R&pf_rd_t=101&pf_rd_p=2346e902-e243-418c-b8af-cad6bb3a6488&pf_rd_i=172282
}

/*
*	categories grabbed from the following URL
*	https://www.amazon.com/electronics-store/b/ref=sd_allcat_elec_hub?ie=UTF8&node=172282
*/
?>