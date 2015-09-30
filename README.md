# DiscogsShippingBundler

For use with www.discogs.com
Working version can be found at http://dbiwer.com/DiscogsBundler.php

##Overview
This application takes a list of albums and traverses discogs.com to 
create a list of sellers that are currently selling more than two
of the listed albums.  This list is emailed to the given email address.

##Instructions
Copy and paste the master or release ID for the albums you want to buy
into the ID box and add to the list.

For example, if I wanted to add the Black Sabbath album "Paranoid", I would
go to http://www.discogs.com/Black-Sabbath-Paranoid/master/302 and copy
the ID on the right side of the page.  In this case, [m302].  There is no need
to copy this ID without the brackets--When it is added to the list, the
brackets will be removed by a jQuery script.

##Minor Features
* Syntax checking on the client and server side
* Email verification
* Strips brackets from the discogs ID.
	(on Discogs, ids are given in the format [m123])
