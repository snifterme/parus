<?php

namespace rokorolov\parus\admin\helpers;

use rokorolov\parus\gallery\models\Album;
use rokorolov\parus\gallery\models\AlbumLang;
use rokorolov\parus\menu\models\Menu;
use rokorolov\parus\menu\models\MenuType;
use rokorolov\parus\blog\models\Post;
use rokorolov\parus\blog\models\Category;
use Yii;

/**
 * SampleDataInstall
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SampleDataInstall
{
    public function init()
    {
        $this->installMenuSampleData();
        $this->installBlogSampleData();
        $this->installGallerySampleData();
    }
    
    public function installMenuSampleData()
    {
        $language = Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemId();
        
        Yii::$app->db->createCommand()->insert(MenuType::tableName(), [
            'id' => 2,
            'menu_type_alias' => 'main_menu',
            'title' => 'Main Menu',
            'description' => ''
        ])->execute();
        
        Yii::$app->db->createCommand()->batchInsert(Menu::tableName(), [
            'status',
            'title',
            'parent_id',
            'menu_type_id',
            'link',
            'note',
            'language',
            'depth',
            'lft',
            'rgt',
        ],
        [
            [
                1,
                'Home',
                1,
                2,
                '/page/index',
                '',
                $language,
                1,
                2,
                3,
            ],
            [
                1,
                'Tech',
                1,
                2,
                'category/show?id=3',
                '',
                $language,
                1,
                4,
                5,
            ],
            [
                1,
                'Mobility',
                1,
                2,
                'category/show?id=4',
                '',
                $language,
                1,
                6,
                7,
            ],
            [
                1,
                'Cloud',
                1,
                2,
                'category/show?id=5',
                '',
                $language,
                1,
                8,
                9,
            ],
        ])->execute();
        
        Yii::$app->db->createCommand()->update(Menu::tableName(), ['rgt' => 10], 'id = 1')->execute();
    }
    
    public function installBlogSampleData()
    {
        $userId = Yii::createObject('rokorolov\parus\user\helpers\DefaultInstall')->getSystemId();
        $language = Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemId();
        $datetime = (new \DateTime());
        
        Yii::$app->db->createCommand()->batchInsert(Category::tableName(), [
            'status',
            'parent_id',
            'image',
            'title',
            'slug',
            'description',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'depth',
            'lft',
            'rgt',
            'language',
            'reference',
            'meta_title',
            'meta_keywords',
            'meta_description',
        ],
        [
            [
                'status' => 1,
                'parent_id' => Yii::createObject('rokorolov\parus\blog\helpers\DefaultInstall')->getSystemRootId(),
                'image' => null,
                'title' => 'Tech',
                'slug' => 'tech',
                'description' => '',
                'created_by' => $userId,
                'created_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'updated_by' => $userId,
                'updated_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'depth' => 1,
                'lft' => 4,
                'rgt' => 5,
                'language' => $language,
                'reference'  => null,
                'meta_title' => '',
                'meta_keywords' => '',
                'meta_description' => '',
            ],
            [
                'status' => 1,
                'parent_id' => Yii::createObject('rokorolov\parus\blog\helpers\DefaultInstall')->getSystemRootId(),
                'image' => null,
                'title' => 'Mobility',
                'slug' => 'mobility',
                'description' => '',
                'created_by' => $userId,
                'created_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'updated_by' => $userId,
                'updated_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'depth' => 1,
                'lft' => 6,
                'rgt' => 7,
                'language' => $language,
                'reference'  => null,
                'meta_title' => '',
                'meta_keywords' => '',
                'meta_description' => '',
            ],
            [
                'status' => 1,
                'parent_id' => Yii::createObject('rokorolov\parus\blog\helpers\DefaultInstall')->getSystemRootId(),
                'image' => null,
                'title' => 'Cloud',
                'slug' => 'cloud',
                'description' => '',
                'created_by' => $userId,
                'created_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'updated_by' => $userId,
                'updated_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'depth' => 1,
                'lft' => 8,
                'rgt' => 9,
                'language' => $language,
                'reference'  => null,
                'meta_title' => '',
                'meta_keywords' => '',
                'meta_description' => '',
            ],
        ])->execute();
        
        Yii::$app->db->createCommand()->update(Category::tableName(), ['rgt' => 10], 'id = 1')->execute();
        
    }
    
    public function installGallerySampleData()
    {
        $language = Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemId();
        $datetime = (new \DateTime());
        
        Yii::$app->db->createCommand()->batchInsert(Album::tableName(), [
            'id',
            'status',
            'album_alias',
            'image',
            'created_at',
            'updated_at',
        ],
        [
            [
                'id' => 1,
                'status' => 1,
                'album_alias' => 'main_slider',
                'image' => null,
                'created_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'updated_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'status' => 1,
                'album_alias' => 'gallery',
                'image' => null,
                'created_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
                'updated_at' => $datetime->modify('+60 seconds')->format('Y-m-d H:i:s'),
            ],
        ])->execute();
        
        Yii::$app->db->createCommand()->batchInsert(AlbumLang::tableName(), [
            'album_id',
            'name',
            'description',
            'language',
        ],
        [
            [
                'album_id' => 1,
                'name' => 'Home page Slider',
                'description' => 'Images for main slider home page',
                'language' => $language,
            ],
            [
                'album_id' => 2,
                'name' => 'Simple photo gallery',
                'description' => 'Images for gallery',
                'language' => $language,
            ],
        ])->execute();
    }
    
    public function installPostSampleData()
    {
        Yii::$app->db->createCommand()->batchInsert(Post::tableName(), [
            'id',
            'category_id',
            'status',
            'title',
            'slug',
            'introtext',
            'fulltext',
            'hits',
            'image',
            'post_type',
            'published_at',
            'publish_up',
            'publish_down',
            'language',
            'view',
            'version',
            'reference',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'deleted_at'
        ],
        $this->getPostSampleData()
        )->execute();
    }
    
    protected function getPostSampleData ()
    {
        $userId = Yii::createObject('rokorolov\parus\user\helpers\DefaultInstall')->getSystemId();
        $language = Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemId();
        
        return [
                array('id' => '1','category_id' => '5','status' => '1','title' => 'IBM acquires hybrid cloud firm Sanovi Technologies','slug' => 'ibm-acquires-hybrid-cloud-firm-sanovi-technologies','introtext' => '<p>The purchase is intended to augment Big Blue\'s disaster recovery services.</p>','fulltext' => '<p>IBM has acquired Sanovi Technologies in order to ramp up the firm\'s hybrid cloud disaster recovery facilities.
              </p><p>Big Blue announced the buyout on October 27, stating the deal will "enhance IBM resiliency capabilities with the 
              help of advanced analytics to meet complexities of hybrid environments."
              </p><p>Financial terms of the purchase were not disclosed.
              </p><p>Based in Bangalore, India, Sanovi Technologies
                      has been working with enterprise players and SMBs for over a decade 
              through providing cloud migration and IT recovery solutions.
              </p><p>The 
              company\'s Application Defined Continuity (ADC) technology is used for 
              workloads across physical, virtual, and cloud infrastructures, and it is
               Sanovi\'s Disaster Recovery Management (DRM) capabilities which are of 
              particular interest to IBM.
              </p><p>For today\'s enterprise players to 
              leverage cloud technology, data centers, and networking effectively, 
              disaster management procedures should be in place to reduce service 
              disruption should something go wrong.
              </p><p>IBM says that Sanovi\'s software will augment the firm\'s existing DRM 
              solutions and will simplify client workflows, help automate the disaster
               recovery process, and reduce recovery time, operational costs, and 
              drill testing time.
              </p>','hits' => '12','image' => 'ibm-acquires-hybrid-cloud-firm-sanovi-technologies','post_type' => 'post','published_at' => '2016-11-07 09:43:12','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:43:12','updated_by' => $userId,'updated_at' => '2016-11-07 11:38:09','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '2','category_id' => '5','status' => '1','title' => 'ServiceNow posts strong Q3 results, Western Digital, Netgear solid','slug' => 'servicenow-posts-strong-q3-results-western-digital-netgear-solid','introtext' => '<p>Western Digital beat estimates despite posting a net loss due to the cost of its SanDisk acquisition.</p>','fulltext' => '<p>Technology earnings on Wednesday were solid with ServiceNow, Western Digital and Netgear all reporting.</p><p>Here\'s the rundown.</p><p><strong>ServiceNow</strong>, makers of a cloud automation platform used for IT service and other functions, reported a third quarter net loss of 36.3 million, or 22 cents a share, on revenue of $357.7 million, up 37 percent from a year ago.</p><p>Non-GAAP
               earnings for the third quarter were 24 cents a share. Wall Street was 
              looking for non-GAAP earnings of 21 cents a share on revenue of $352.2 
              million.</p><p>ServiceNow CFO Michael Scarpelli said the company\'s subscription billings were $363 million and grew 47 percent year-over-year. </p><p>For
               the fourth quarter, ServiceNow expects total revenues between $376 
              million and $381 million, which is in line with analyst estimates. For 
              the year, ServiceNow expects about $1.38 billion.         </p><p>ServiceNow\'s shares jumped nearly five percent in late trading. </p><p><strong>Western Digital</strong>\'s fiscal first quarter sales exceeded expectations thanks to the rebounding PC market and demand for its hard drive and flash-based products.</p>','hits' => '14','image' => 'servicenow-posts-strong-q3-results-western-digital-netgear-solid','post_type' => 'post','published_at' => '2016-11-07 09:44:44','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:44:44','updated_by' => $userId,'updated_at' => '2016-11-07 11:37:54','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '3','category_id' => '5','status' => '1','title' => 'Where OpenStack cloud is today and where it\'s going tomorrow','slug' => 'where-openstack-cloud-is-today-and-where-its-going-tomorrow','introtext' => '<p>The future looks bright for OpenStack -- according to 451 Research, OpenStack is growing rapidly to become a $5-billion-a-year cloud business. But obstacles still remain.
              </p>','fulltext' => '<p>OpenStack Summit has 5,000-plus people who believe that OpenStack is the future of the cloud. 451 Research
               thinks they may be onto something, The research company expects revenue
               from OpenStack business models to exceed $5 billion by 2020 and grow at
               a 35 percent compound annual growth rate (CAGR).<br /></p><p>The total revenue is small potatoes compared to Amazon Web Services (AWS), but the growth rate is great. </p><p>451
               observed that so far OpenStack-based revenue has been overwhelmingly 
              from service providers offering multi-tenant Infrastructure-as-a-Service
               (IaaS). Looking ahead, though, 451 believes OpenStack\'s future success 
              will come from the private cloud space and in providing hybrid-cloud 
              orchestration for public cloud integration.  Better still, for OpenStack
               companies, 451 sees private cloud revenue exceeding public cloud by 
              2019. </p><p>This focus on the private cloud was clearly visible in the OpenStack Summit keynote speeches. Tech leaders from Banco Santander, a Spain-based international bank, and Sky,
               one of Europe\'s leading television and internet companies, both sang 
              the praises of OpenStack on the private cloud. Both companies are also 
              using a distribution-based OpenStack approach: Red Hat Enterprise Linux (RHEL) for Santander and Ubuntu for Sky. </p><p>451
               Research also predicted that OpenStack will grow across 
              software-defined networking (SDN), network function virtualization 
              (NFV), mobile, and Internet of Things (IoT) for both service providers 
              and enterprises. This is in addition to its existing use cases in big 
              data and lines of business. The keynotes, again, supported this 
              conclusion. Representatives from Huawei, NEC, and Nokia all sang 
              OpenStack\'s praises in business and telecom.         </p><p>That\'s not to say that everyone thinks OpenStack will do best in private or hybrid cloud environments. Germany telecom giant Deutsche Telekom is using OpenStack as the foundation for its public cloud. </p><p>But it\'s not all wine and roses for OpenStack looking ahead. </p><p>"This
               year OpenStack has become a top priority and credible cloud option, but
               it still has its shortcomings," said Al Sadowski, 451 Research\'s 
              research VP.  For example, while OpenStack is still growing in 
              popularity for enterprises interested in deploying private cloud-native 
              applications, its appeal is limited for legacy applications and for 
              companies that are already comfortable with AWS or Microsoft Azure. </p>','hits' => '18','image' => 'where-openstack-cloud-is-today-and-where-its-going-tomorrow','post_type' => 'post','published_at' => '2016-11-07 09:46:02','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '3','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:46:02','updated_by' => $userId,'updated_at' => '2016-11-05 10:50:31','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '4','category_id' => '5','status' => '1','title' => 'Google plans to sell its own Microsoft Surface Hub alternative for under $6,000 next year','slug' => 'google-plans-to-sell-its-own-microsoft-surface-hub-alternative-for-under-6000-next-year','introtext' => '<p>Google\'s Jamboard is a hardware/software touch-display appliance that seems to be aimed squarely at Microsoft\'s Surface Hub.</p>','fulltext' => '<p>A day before Microsoft is expected to unveil a new member of its Surface device family, Google is taking the wraps off its planned alternative to the Surface Hub: Jamboard.<br /></p><p>Google is opening up applications for Early Adopter versions of Jamboard
               today, October 25. Selected applicants will begin receiving their 
              Jamboards as soon as next week, officials said. Google\'s estimated ship 
              target for Jamboard is the first half of 2017.</p><p>Google plans to make Jamboard part of its G Suite,
               so that users will be able to integrate Docs, Sheets, Slides, and 
              photos stored in Drive, directly into Jamboard. As of now, there are no 
              plans to have these apps run natively on the Jamboard itself, officials 
              said. Instead, they\'ll use companion smartphone and tablet Jamboard 
              applications (for iOS, Android, and Chrome OS) to participate in 
              Jamboard "jams," which are backed up to Google Drive. Jamboard uses 
              Google Hangouts and Google Cast for setting up collaborations and 
              broadcasts.</p>        <p>The
               Jamboard hardware consists of a 55-inch, 4K, 60Hz Ultra HD Touch 
              display, which will run a variant of Android Marshmallow. (Google is not
               yet disclosing which processor is inside.) The device will support 
              passive stylus and finger recognition; 16 touch points; USB 2.0, 3.0, 
              Type C, HDMI 2.0, Bluetooth, and NFC;  and comes with a built-in 
              wide-angle HD camera, mic, speakers, and Wi-Fi. </p><p>Microsoft has been championing touch collaboration team meeting hardware, software, and services since it bought large-display vendor Perceptive Pixel in 2012. </p>','hits' => '28','image' => 'google-plans-to-sell-its-own-microsoft-surface-hub-alternative','post_type' => 'post','published_at' => '2016-11-07 09:47:38','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '4','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:47:38','updated_by' => $userId,'updated_at' => '2016-11-07 12:01:19','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '5','category_id' => '5','status' => '1','title' => 'Dropbox partners with Blackboard to push further into the education sector','slug' => 'dropbox-partners-with-blackboard-to-push-further-into-the-education-sector','introtext' => '<p>Dropbox will be natively integrated into Blackboard\'s learning 
              management system, bringing its cloud file-sharing services into more 
              classrooms.</p>','fulltext' => '<p>Dropbox on Tuesday announced its latest major collaboration, this 
              time in the education space. The cloud-based file sharing company is 
              partnering with Blackboard, the provider of the leading learning 
              management system (LMS) for both K-12 schools and universities, to 
              natively integrate Dropbox into the LMS product Blackboard Learn.</p><p>Dropbox is focused on providing a content collaboration platform
               that can work on any number of applications. As part of that effort, 
              the company is heavily invested in building key partnerships. </p><p>"We\'re
               making some very big bets on which one of those applications are 
              important, and Blackboard is a key one for us in the education space," 
              Billy Blau, Dropbox\'s head of technology partnerships, told ZDNet. </p><p>When Dropbox launched Dropbox Education
               earlier in May -- its file sharing service designed specifically for 
              colleges and universities -- it offered a lightweight integration with 
              Blackboard\'s mobile app. </p><p>Now, Dropbox will be embedded directly 
              into Blackboard Learn, enabling students to collaborate on projects 
              together and submit them to Blackboard through Dropbox, and allowing 
              professors to communicate with students. While many Dropbox users are 
              already collaborating with Blackboard, Dropbox is confident that the LMS
               provider -- with 100 million users -- will advance Dropbox\'s push into 
              the education sector. When Dropbox Education launched, it had more than 
              4,000 univerities on board, and it now has about 6,000 universities and 
              institutions using it globally.  </p><p>The new integration, however, is
               available for all Dropbox products, not just Dropbox Education. Dropbox
               is the only enterprise file-syncing and sharing company with this 
              relationship with Blackboard.         </p><p>Blau said Dropbox will continue to build these kinds of partnerships. "You\'ve seen it with Office 365 and Adobe
               -- deep product integrations are helping drive significant business for
               us," he said. "Users don\'t want to toggle back and forth between 
              applications, they want to see it work all in one place."</p>','hits' => '34','image' => 'dropbox-partners-with-blackboard-to-push-further','post_type' => 'post','published_at' => '2016-11-07 09:48:29','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '3','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:48:29','updated_by' => $userId,'updated_at' => '2016-11-07 12:02:33','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '6','category_id' => '4','status' => '1','title' => '5 Android navigation apps for those who are sick of Google Maps','slug' => '5-android-navigation-apps-for-those-who-are-sick-of-google-maps','introtext' => '<p>If you think Google Maps is the best navigation app for Android, you haven\'t seen the competition.</p>','fulltext' => '<p> 	 	 	Don\'t get me wrong: Google Maps
               is the king of the online mapping industry, but that doesn\'t mean that 
              the Google Maps app is the best navigation app for your Android phone. 
              There are plenty of other navigation apps on the market, and most of 
              them are just as speedy and accurate as Google Maps (some are more so). 
              Google Maps is a good 
                      <em>general </em>maps app, but if you commute all day long or you primarily use public transit, it may not be the best app for you.
              </p><p> 	  Here are five other Android navigation apps you might want to check out:
              </p><h3><strong>Waze</strong></h3><p> 	  Waze is technically owned by Google, but this "social" navigation app is completely separate from Google Maps. While Google Maps is a general navigation app, Waze
               is driving-focused, which means it offers some cool features like 
              user-reported gas prices and turn-by-turn directions to parking lots 
              near your destination. The app also has a robust community of users, and
               it uses this community to gather real-time traffic data and 
              user-reported incidents, such as accidents, road closures, speed cameras
               and police traps.
              </p><p> 	  If you\'re looking for a friendly driving 
              community that\'s committed to keeping the public informed about upcoming
               speed traps, Waze is the app for you. This app is all about social 
              networking, so you can also plan trips with friends, sync events from 
              your phone\'s calendar as well as your Facebook account and see the ETAs 
              of friends who are traveling to the same place as you are.
              </p><span class="caption"></span><p>Here WeGo was originally developed for the Windows Phone platform, and is now on Android as well as iOS.
              </p>                                            Sarah Jacobsson Purewal/CNET<h3><strong>Here WeGo</strong></h3><p>Here WeGo
               (which was originally developed for the Windows Phone platform by 
              Nokia) is a free app that offers turn-by-turn directions for drivers, 
              pedestrians, cyclists and public transit riders. The app also features 
              real-time traffic information, nearby points of interest, the ability to
               share your location (or the location of a point of interest) with 
              friends and family members and offline map downloads for when you don\'t 
              have a data connection.
              </p><p> 	  Here WeGo offers just about everything
               Google Maps does, and a little more. The cycling directions and the 
              public transportation overlays are useful, and the app\'s points of 
              interest system is also pretty strong (the app pulls info from places 
              like Wikipedia, TripAdvisor, BlaBlaCar, Expedia, Car2go and 
              GetYourGuide).
              </p><h3><strong>Moovit</strong></h3><p> 	  If you take public transportation frequently, you\'ve probably noticed that Google Maps\' directions are very basic. Moovit
               is a free, dedicated transit app that offers several features you won\'t
               find in Google Maps, such as step-by-step directions that will tell you
               how many stops you have left and alert you when it\'s time to get off 
              the bus, train or ferry you\'re riding. Moovit also features an entire 
              section dedicated to information about delays, maintenance and service 
              interruptions. It also links you to the local transit agencies\' Twitter 
              accounts, so you can 
                      <em>really </em>feel like a local.
              </p><span class="caption"></span><p>Are you usually on public transportation? Moovit is designed especially for it
              </p>Google Maps\' public transit directions<p> will work in a pinch, but Moovit -- which is available in 1,200 cities </p><p>around the world (including over 100 cities in the US) -- is the app you</p><p> need to really understand the local metro system. The app has even </p><p>started integrating information about local bike shares in select </p><p>cities.</p>','hits' => '6','image' => '5-android-navigation-apps-for-those-who-are-sick-of-google-maps','post_type' => 'post','published_at' => '2016-11-07 09:52:32','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:52:32','updated_by' => $userId,'updated_at' => '2016-11-07 11:36:58','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '7','category_id' => '4','status' => '1','title' => 'The Samsung Galaxy S8 could be mostly screen','slug' => 'the-samsung-galaxy-s8-could-be-mostly-screen','introtext' => '<p>A recent Korean report suggests that the front of the next Samsung 
              Galaxy phone will be almost entirely dedicated to the display.</p>','fulltext' => '<p>The upcoming Samsung Galaxy S8 could have a whole lot of display, at least according to a recent report by Korean publication The Investor.</p><p>The report from The Investor, comes after the iMiD 2016 display exhibition that took place in Seoul last week. According to the report, Park Won-sang, an engineer for Samsung\'s
               display manufacturing unit, said that the front of next year\'s phone 
              will be over 90 percent screen space. The average display area ratio 
              (which is the percentage of the front of the phone taken up by the 
              screen) of current smartphones
               is around 80 percent, according to The Investor report. If this rumor 
              is true, this would mean that the S8 could feature at least 10 percent 
              more screen than earlier devices. It is unknown how Samsung\'s famous curved displays would count for this percentage.</p><p>But
               Samsung isn\'t planning on stopping at 90 percent. According to the same
               engineer, Samsung could aim for a device that is up to 99 percent 
              screen. This reflects an ongoing trend to make smartphones mostly 
              screen. Recently we got to see the Xiaomi Mi Mix,
               a phone that is almost entirely screen. Regardless of whether this 
              Galaxy S8 rumor is true, it wouldn\'t be surprising to see more phones 
              come out next year with higher display area ratios.</p><p>The Investor 
              report also adds a few other speculations about what to expect from the 
              upcoming Galaxy S phone. This includes an OLED display, and a bezelless 
              body.  Although the Galaxy S8 has not yet been officially announced, these new rumors fall in line with previous rumors
               that suggest a full-screen display. Additionally, the existence of a 
              full-screen display on the S8 corresponds with the idea that it would 
              get rid of the home button and feature a fingerprint sensor under the display.</p><p>However,
               until Samsung unveils its next Galaxy S phone, these rumors should be 
              regarded as only speculation. Samsung did not immediately respond to 
              CNET\'s request for comment.</p>','hits' => '8','image' => 'the-samsung-galaxy-s8-could-be-mostly-screen','post_type' => 'post','published_at' => '2016-11-07 09:53:34','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:53:34','updated_by' => $userId,'updated_at' => '2016-11-07 11:36:38','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '8','category_id' => '4','status' => '1','title' => 'The iPhone 8 could have wireless charging','slug' => 'the-iphone-8-could-have-wireless-charging','introtext' => '<p>Apple manufacturing partner Foxconn is reportedly testing wireless charging for the next iPhone.</p>','fulltext' => '<p>The "iPhone 8" could have wireless charging.</p><p> While it  	 has been previously reported that Apple will introduce wireless charging into its phones, Foxconn Technology Group, one of Apple\'s main manufacturing partners, is making wireless charging modules for the 2017 iPhone, Nikkei Asian Review reports.</p><p>  	Wireless charging has been around for a few years, and manufacturers  <span class="link">such as Samsung</span> have been quick to incorporate this technology into their phones. While only the Apple Watch uses it so far, previous reports indicate that Apple has been acquiring engineers from wireless charging companies and cite 2017 as the year we will see this technology.</p><p>
               2017 will be the 10th anniversary of the iPhone\'s release, and it seems
               like a good bet that Apple has big plans, possibly including wireless 
              charging.</p><p> 	 		 	 	 	 The Nikkei report also speculates that the   	
                      iPhone 8
                   will come with a curved OLED display. While this has not been confirmed by Apple, Sharp President Tai Jeng-wu mentioned that the next iPhone would have an OLED screen during a speech last week.</p> 	While the Nikkei report states that  	Foxconnis already producing the wireless charging module for the next iPhone, this does not necessarily mean it will make it into the version of the phone that\'s released to the public. Nikkei\'s source states that the release of the technology will "depend on whether Foxconn can boost the yield rate to a satisfactory level later on." Put simply, the factory has to get better at making the units before they have a shot at making it into the final.','hits' => '8','image' => 'the-iphone-8-could-have-wireless-charging','post_type' => 'post','published_at' => '2016-11-07 09:59:15','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 09:59:15','updated_by' => $userId,'updated_at' => '2016-11-07 11:36:25','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '9','category_id' => '4','status' => '1','title' => 'Google\'s Daydream View VR headset hits stores November 10','slug' => 'googles-daydream-view-vr-headset-hits-stores-november-10','introtext' => '<p>The tech giant\'s plush, fabric-covered VR headset will launch in five countries next week and is available to order now.</p>','fulltext' => '<p>Google\'s much fancier follow-up to its Cardboard virtual-reality headset, the Daydream View, now has an official release date: November 10. </p><p>The tech giant unveiled the plush, fabric-covered Daydream View headset
               and its controller last month. The device, which will cost $79 in the 
              US, will hit the Google Store and retailers in five countries next week,
               the company said in a blog post
               Tuesday. In addition to the US, it will be available in the UK (£69), 
              Australia (AU$119), Germany and Canada. The Daydream View, which comes 
              in slate gray, can also be ordered through Google\'s online store and 
              will ship by November 10. </p><p>The VR headset is powered by a Daydream-ready phone, such as Google\'s new Pixel phone, which was announced at the same splashy event last month. The company showed off Daydream, its VR hardware and software platform, at its developer conference in May.</p>','hits' => '12','image' => 'googles-daydream-view-vr-headset-hits-stores-november-10','post_type' => 'post','published_at' => '2016-11-07 10:00:34','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 10:00:34','updated_by' => $userId,'updated_at' => '2016-11-07 11:36:07','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '10','category_id' => '4','status' => '1','title' => 'Lenovo and Google finish their Tango (phone). Should you join in?','slug' => 'lenovo-and-google-finish-their-tango-phone-should-you-join-in','introtext' => '<p>Lenovo\'s Phab 2 Pro goes on sale today (shipping in 4-5 weeks). But it\'s
               just the first in a wave of phones with Google\'s 3D-sensing Tango 
              camera.</p>','fulltext' => '<p>Google\'s Tango was playing hard to get -- but eventually, Lenovo captured its heart.</p><p>Today, you can finally order the Lenovo Phab 2 Pro, the first phone with Google\'s 3D-sensing Tango camera technology
               at its core. It measures objects just by looking at them. It\'s the 
              phone that could let you see how a new piece of furniture might actually
               fit in your home, or play advanced Pokemon Go-like augmented reality games in the real world. </p><p>And unlike <span class="link">some augmented reality products</span>,
               the Phab 2 Pro isn\'t just a developer kit. It\'s an actual 
              consumer-grade phone with upper midrange specs, a huge 6.4-inch screen 
              and a big battery, all for a relatively competitive price ($499). </p><p>But Google now says the Phab 2 Pro is just the <em>first of many</em> Tango phones headed to market in 2017 -- so now may not be the best time to join in.</p><p><img src="https://cnet2.cbsistatic.com/img/Q1AggsOLvni1QNsbw4iDw4wE030=/fit-in/970x0/2016/10/31/e2486c6e-ca89-4fdd-b2b0-bd8cf03c4b6d/google-tango-lenovo-1940-012.jpg" alt="google-tango-lenovo-1905-001.jpg" /></p><h3><strong>The rose still has thorns</strong></h3><p>I\'m
               not evaluating the Phab 2 Pro as a phone. It could be an excellent 
              Android phone with stellar battery life and a great camera, but I don\'t 
              know for sure. (I can tell you right now that the glass-and-metal 
              chassis feels high-quality, but we\'ll save the rest for our full 
              review.)</p>','hits' => '29','image' => 'lenovo-and-google-finish-their-tango-phone-should-you-join-in','post_type' => 'post','published_at' => '2016-11-07 10:02:06','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '3','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 10:02:06','updated_by' => $userId,'updated_at' => '2016-11-07 11:35:46','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '11','category_id' => '3','status' => '1','title' => 'Apple\'s new MacBooks, Twitter\'s woes and Uber\'s flying cars','slug' => 'apples-new-macbooks-twitters-woes-and-ubers-flying-cars','introtext' => '<p>The UK\'s best tech podcast covers Apple\'s new MacBooks, Twitter\'s woes and why your next Uber might take you to the skies.</p>','fulltext' => '<p>Twitter has given Vine the Old Yeller treatment in a bid to raise its profits. But what on Earth will happen to all those looping videos of kittens yawning?</p><p>It\'s not all doom and gloom though as Apple has taken the wraps off some new MacBooks, which come with a fancy new touch-enabled strip. CNET got an exclusive early look at the new devices, as well as some time with the brains behind them.</p><p>Also up for discussion on the UK\'s best tech podcast is Microsoft\'s neat new desktop PC and why Uber has its eyes on the skies.</p>','hits' => '0','image' => 'apples-new-macbooks-twitters-woes-and-ubers-flying-cars','post_type' => 'post','published_at' => '2016-11-07 10:07:07','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '3','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 10:07:07','updated_by' => $userId,'updated_at' => '2016-11-07 11:35:31','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '12','category_id' => '3','status' => '1','title' => 'Samsung pours $1 billion into boosting chip production in US','slug' => 'samsung-pours-1-billion-into-boosting-chip-production-in-us','introtext' => '<p>The electronics titan wants to increase capacity at its Austin, Texas facilities.</p>','fulltext' => '<p>Samsung, sore from the disastrous <span class="link">Galaxy Note 7</span> debacle, is betting big on chips. </p><p>The
               company said in a statement that plans to invest more than $1 billion 
              by the end of June 2017 to increase the production of system chips in 
              its Austin, Texas, facilities. </p><p>While Samsung is best known for 
              televisions, phones and refrigerators, it also makes many of the 
              components that go into these devices. Certain Samsung phones, for 
              instance, are powered by its Exynos chip, which is the equivalent of the
               Qualcomm Snapdragon processor that serves as the brain for other 
              phones. </p><p>The company plans to boost production on system chips for mobile devices and other electronic gadgets. </p><p>"We
               are committed to Austin and our contributions to the community," said 
              Catherine Morse, general counsel and senior director of public affairs 
              at Samsung Austin Semiconductor. "This is our home and we want to ensure
               our community is healthy and prospering. These investments will support
               this, while also ensuring our customers\' growing needs are met."</p>','hits' => '0','image' => 'samsung-pours-1-billion-into-boosting-chip-production-in-us','post_type' => 'post','published_at' => '2016-11-07 10:08:38','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 10:08:38','updated_by' => $userId,'updated_at' => '2016-11-07 11:35:16','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '13','category_id' => '3','status' => '1','title' => 'Sony says goodbye to battery business, cuts profit forecast','slug' => 'sony-says-goodbye-to-battery-business-cuts-profit-forecast','introtext' => '<p>The company is relying on the PlayStation 4 game console and the PlayStation VR headset to rake in money this year.</p>','fulltext' => '<p>Sony announced the sale of its battery business on Monday and, at the
               same time, reduced its profit estimate for the fiscal year.</p><p>The Japanese tech titan is still looking at the <span class="link">PlayStation 4</span> game console and the <span class="link"><a href="https://www.cnet.com/products/sony-playstation-vr/" rel="nofollow">PlayStation VR</a></span>, its virtual reality headset, to contribute to a healthy financial year for the company. </p><p>The sale of the battery business is meant to contribute to this goal. The business was sold to Murata Manufacturing,
               a components company also based in Japan, for $166 million. The sale is
               part of a Sony\'s long-term plan to restructure the company around video
               games, entertainment and the camera sensors that feature in the many of
               the market\'s best-selling phones.</p><p>Sony said it still expects
               to post a profit of $2.6 billion when its financial year ends in March,
               but that is $300 million less than it estimated back in July.</p>','hits' => '1','image' => 'sony-says-goodbye-to-battery-business-cuts-profit-forecast','post_type' => 'post','published_at' => '2016-11-07 10:10:20','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 10:10:20','updated_by' => $userId,'updated_at' => '2016-11-07 11:35:02','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '14','category_id' => '3','status' => '1','title' => 'Readfeed brings online book clubs to Android','slug' => 'readfeed-brings-online-book-clubs-to-android','introtext' => '<p>The app is one of the first to graduate from Google\'s Early Access open beta program.</p>','fulltext' => '<p>A new app called Readfeed has officially launched on Google Play, and it\'s calling itself "the world\'s largest online book club".</p><p>"Readfeed
               was created to help book lovers around the world share and discuss 
              their favorite reads with each other more easily," said founder Rajiev 
              Timal in a guest post on Google\'s blog.</p><p>It\'s one of the first 
              online book clubs for Android. There\'s a full social platform that lets 
              you connect with other users who have read the same books as you, and 
              with Readfeed, you can create virtual bookshelves, custom reading lists,
               track your progress and chat with other users. </p><p>More importantly, it\'s also one of the first apps to graduate from Google\'s Early Access program.</p><p>Early Access
               allows app developers to release Android apps in open beta for anyone 
              to test out ahead of the official launch while the final kinks are 
              ironed out. In return, the developers got useful feedback from people 
              using the app out in the wild.</p><p>"Readfeed has come a long way since
               we first released the app as beta on Google Play\'s Early Access 
              program. As one of the first graduates of the beta program, we were able
               to solicit feature requests, identify bugs, locate new and optimize 
              existing target markets, as well as build a sizable reader community. 
              This allowed Readfeed to deliver the best possible experience right out 
              of the gate," said Timal.</p><p>The Play Store has other unreleased apps
               and in development games available for free testing now -- just open 
              the Play Store and go to the Early Access section at the end of the main
               list.</p>','hits' => '0','image' => 'readfeed-brings-online-book-clubs-to-android','post_type' => 'post','published_at' => '2016-11-07 10:11:57','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '2','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 10:11:57','updated_by' => $userId,'updated_at' => '2016-11-07 11:34:36','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
                array('id' => '15','category_id' => '3','status' => '1','title' => 'WhatsApp\'s creators say they\'re committed to users\' privacy','slug' => 'whatsapps-creators-say-theyre-committed-to-users-privacy','introtext' => '<p>Messaging app co-founders say that users communicating with businesses will not attract spam.</p>','fulltext' => '<p>The creators of WhatsApp say they\'re staying true to their formula of
               simply letting people connect with each other and now with businesses, 
              all with privacy in mind.</p><p>Co-founders Jan Koum and Brian Acton explained Tuesday during the Wall Street Journal\'s WSJ.D Live
               global technology conference in Laguna Beach, California, that the 
              instant messaging app\'s plans to help its one billion users better 
              communicate with businesses is much about necessity and demand.</p><p>"I
               haven\'t met anybody who is on hold [while on the phone] and gets 
              excited about it," said Koum, attracting laughs from the crowd. "If we 
              can apply communications with businesses, people\'s lives will be a lot 
              better."</p><p>Acton added, "There was pent-up demand. It meant that we really needed to build this."</p><p>This includes a bank sending a warning about a potentially fraudulent
               transaction or receiving a notification a flight delay. To make this 
              happen, however, WhatsApp is sharing users\' phone numbers with its 
              parent Facebook, which drew some criticism over privacy. </p><p>The significant change comes more than two years since being acquired by social media behemoth Facebook.
               While the change makes it easier for businesses to contact users, Koum 
              and Acton both promised users\' privacy won\'t be compromised and there 
              will be "no spam."</p><p>Despite users\' phone numbers being linked to 
              Facebook, Koum reiterated they will be secure thanks to the introduction
               two years ago of end-to-end encryption two. </p><p>"We never really had
               a lot of information about our users to begin with," he said. "We never
               asked our users for their names, for their gender, for their ages or 
              where they live, so it\'s not like we\'re sitting on this wealth of 
              information.</p><p>"We always built our system in a way [so we] know as little information about our users as possible," Koum concluded. </p><p>Regarding
               Facebook\'s role in the company, Koum and Acton said the social media 
              giant has been very supportive and somewhat hands-off. </p>','hits' => '11','image' => 'whatsapps-creators-say-theyre-committed-to-users-privacy','post_type' => 'post','published_at' => '2016-11-07 10:12:58','publish_up' => NULL,'publish_down' => NULL,'language' => $language,'view' => '','version' => '5','reference' => '','created_by' => $userId,'created_at' => '2016-11-07 10:12:58','updated_by' => $userId,'updated_at' => '2016-11-07 15:54:19','meta_title' => '','meta_keywords' => '','meta_description' => '','deleted_at' => NULL),
        ];
    }
}