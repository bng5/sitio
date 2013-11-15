<?php

class PabloController extends Controller {

    public $prefix;
        
    public $ssh_keys = array(
        'pablo_wintermute' => array(
            'id' => 'pablo@wintermute',
            'fingerprint' => '49:6f:c2:96:9d:fe:9c:5c:43:1e:7e:47:fa:31:b7:39',
            'content' => 'ssh-rsa AAAAB3NzaC1yc2EAAAABIwAAAQEA3m55STZ3VsQ7hLzPlaCxv7iz8zaIGh/1PImxXVUWjiZlN9X9nZvs3OtQaRrhCKkzpQ/PetXAq/Qo+HW+xMlb9clW4nRWwzT5dN9w5/L81G8eOlHGQ83+RMC4feUmsQ6ePYKzY6P4Yzd753LYpsD5//0GESSXQ7cVzLfKUsgKsu5DGHVE6H+HmTO68nyiOdvmz05XYTbbvNyXY02cgvR9LBpsA+34n5aqwArsv/o7MNxz27fsPjCqeuyG6uaqiyZ1qn9N4XX+S6AfibvAFq4rQaP/pZTSqcwg2bkIeRJYQ0mNTeh2mvwsqvJxL1/61qif8VRsNj2mqaOaWXA+nuuJuw== pablo@wintermute',
        ),
        'bng5_gator862.hostgator.com' => array(
            'id' => 'bng5@gator862.hostgator.com',
            'fingerprint' => '67:e2:9f:ea:8b:30:a4:4d:b6:01:03:bb:ed:c2:3d:f9',
            'content' => 'ssh-rsa AAAAB3NzaC1yc2EAAAABIwAAAQEAntjCJVGLxjTdAPzIWcSle8DnGhVRGDQo4Cl3ghbT+4NSZKJRUWEVklEvRDqK9UCy/QLiwXZDqytL2GXT4j3Q4IJ5xjo3dugYgnlDiwg7Z5k6QU7WRFPMxTNSvX6ATqSDS0WZLFOdV3bqOQtMvLd/p0z6JbyQIBaFZ2dGSKwyzaTnBMZ+WuzGNJI2xTs8YpDW2Ms0/tdxOyN1apJnoT9s2k1wv0CECfzVkrQ1QOiIvhP+0Zwf84Dc+jrgzL61cuQS/Bjivisy+Ntg0gnkfiBgLtjdS20L+MEU6U1xfOY0asXkgQY9b77NhN7c9UvEQ5XGEED/ZsyeJhV9BRNZBsKceQ== bng5@gator862.hostgator.com',
        ),
    );
    
    public $keys = array(
            'A89B7471' => array(
                'pub' => '2048R/<a href="%s">A89B7471</a>  2013-05-22 [[caduca: 2015-05-22]]',
                'uid' => 'Pablo Bangueses &lt;pablo@bng5.net&gt;',
                'sub' => '2048R/939E453A 2013-05-22 [[caduca: 2015-05-22]]',
                'fingerprint' => 'C4F7 5463 ED67 675D BF62  E3DD 48FD 1BB2 A89B 7471',
                'content' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: SKS 1.1.0

mQENBFGdAocBCACwgfdL8qH4TdIVxw6bywoqp/xFT/hyREK7sPhFVT+fFe7FY01ZfmZ+YY0B
1YglE0uPDXKkwe0YdyLq4lom9BbCj7/0OqN0yUkZXwuV7KFYm5ydfWpXCWgrZt19RaxK/ezM
ToWQtQNs3FzEsxkNM5K8kGlOfxqarg/302ISsWryIQW2aZA/SnIlTvqMwWkYkXDJSGeCB9jA
181VUPpFuKr+WFVo7frB6Seuop3cdo0GnYQ0UWvIfEcdSJw+yvyZ+SdjTYbWl7FHnhWfUHsC
vlZL1+aZiJ71EMLqV+ZgZ1BV29UBK8JRvfHFCy0g2Iwbd6CygEbI5raJ5Yq0EIbL46BpABEB
AAG0IFBhYmxvIEJhbmd1ZXNlcyA8cGFibG9AYm5nNS5uZXQ+iQE+BBMBAgAoBQJRnQKHAhsj
BQkDwmcABgsJCAcDAgYVCAIJCgsEFgIDAQIeAQIXgAAKCRBI/RuyqJt0cdssB/sFywHRVi75
7KwYrzgzyq8pLywNgp94F6QclPEM4WZh6iqQhTQQPXuoqeTEzSfkVjKE8eLY6QAFl0Bft80q
DC6USesOWqXa96hhPSxFBP7AAFyPDXciR8Z7PWvuzRLYvYkrSBk6rB2+pAL1VlalZownIYPr
K2SkQRtCMxAlTtE+7qqbJCCpD8EJC76w5kNXbO33dZmErm/qIePpDbR/QQMIrJb+r/cSaCyY
ohwmeRCvmuUjVtcdNWhUWfsU9eLr4FnMv+P15gw4drjPrESJ5wdOJeCzRBzcblsD0rH1RxUX
b5ADzo2RYTBdL9Q3xDmZTL/lrAgayMzUxVhbDcEfNFruuQENBFGdAocBCACftnSIQ689pPnm
gp3ZrOjHR4tPhvxy+2vYXtceP7H1p80d277ZFsebLtMFn9ouXU2mOf7+khscblPuI9VgJtbv
u2rNOZyAn0XHMMpKfFfNMwWZ7GveDdY4qcUIZ49cqbnfO6ZJtfwO7KYkhP8UEuOB50XzvDG2
6Fdleb+IyQm5KKwoppiQhqkn5HxwzzdaRUUDvx75P8MVwEDwWOAtq2cz/XgCAL0uAqmcIK0C
Wc+Tw3Z3XtRvjP+EMDFEOqN7+pi4TV9AzT9FBeWWfxCJ++4gIruNGhzn5loKeZmCLqIRWDmo
n/hqVTexr0o5Fh/isWDYf8OCzzavvRaEY9Da2KohABEBAAGJASUEGAECAA8FAlGdAocCGwwF
CQPCZwAACgkQSP0bsqibdHFVOAf/UXimqUOTTWneh2AM00qzlKqUZcqpG3pYLwTKaEigJvx8
tb8bxfv1EZFpwJky1GsWT5ms4n+fPf8dGGpkqsvL8UcsIjEdFP3rje3tJq31bZBTHQHbTfvN
ZNwDKQUG7zqT8VW8dCwEYbDAhRLvhHzBbLAYp0nk4Ciqk/PsFzJO/bAPRghmBF/zfkKhdkJk
Hw8RNxmwvQH8n0irlUzFkyxKGKGYWKd/h2U4w5h8X3JfOPSaP0k99TFD6nnz68o/mvX6TfnX
2KP0ZkvoetLJ+3fG5s2LJ8mImTuQHvj3Xfit7Q/MLdbyxmyCSusgg8jVOc+Hp27TWbeTfPe9
EcrjoUWvTw==
=+Asg
-----END PGP PUBLIC KEY BLOCK-----',
            ),
            '88C5FA8B' => array(
                'pub' => '1024D/<a href="%s">88C5FA8B</a> 2011-03-08 [<span style="color: #800000;">revocada</span>]',
                'uid' => 'Pablo Bangueses (Bng5) &lt;pablo@bng5.net&gt;',
//                'sub' => '2048g/B8650CD8 2011-03-08 __________ 2013-03-07',
                'fingerprint' => 'FCE5 CDD0 1EEE CDF8 49A4 AF68 B7A6 18E6 88C5 FA8B',
                'content' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1.4.6 (GNU/Linux)

mQGiBE12hMgRBAC45T/LgMIB1HdHSCgcfLW4f1JHZ67vLgPfJzMidI2iHi9T/Zyn
44e75R4kneF0bqgyo+6ALH3gQVzSz/WKsFan+YEhVK4xxuQ2OTu/HSw/OKobKTyZ
tAMPD9v3pzfosw5YFyu0YfAEYnT9/szPwqTKxA91kyvHEkLbHW46/wcUwwCg4rNT
4kRxtilYgN0qn9VCK3DZGOcD/jgwo38fYirDUX2JN5iXzMgqT2oCmB/UrSy8n2sE
TIDCVHbBO+dMpPO/OT1/WpurEq6XSsakSv4Hxrbje3AFb28V+w+skkOvzadEbaiD
uHqGv8ExOdfV+gw1at4GLyVwTmQHVy0L1ZhY1lJ2iy37BwlnUAzgkcDJ7FN2kCXA
K9ZZBACDdcm4WWpxxR/GnRplFXICPqMWuttkhIyUu5SvoxwLXctSpB1IIXqstNE0
lwxlTG0fufVVlYnh49hFB6UEFNBUOj8eDOvWhqaBft9i1MrTdYePJby3ZbBdlMcN
EQRV3ZSSIIEcoIY2AEfN5WD9euR4CoMiB7xiFowJ8O6IW+0y1LQnUGFibG8gQmFu
Z3Vlc2VzIChCbmc1KSA8cGFibG9AYm5nNS5uZXQ+iGYEExECACYFAk12hMgCGwMF
CQPCZwAGCwkIBwMCBBUCCAMEFgIDAQIeAQIXgAAKCRC3phjmiMX6i8hGAJ9Uw9eQ
zchTAueVcFhiPY4kqDXUEwCg1zfn3bMxEBCaCUEZlrhkbdpq/ea5Ag0ETXaE0BAI
AOy6xfIfM55z81g/d3fv5PIAtQTmn1mtSDpVsppUl2MbgstUjrSI3QaKcJq4fEcq
B/lhdCSk8i2bzJZmCZ3JCYWmUPtOe/kXd4lvOkr8pjaBA4Y8Do7MWjS2/0FyjtJ9
WnCUc62LovP2UlzFUJ2slr0IGltCcN9csznVJIsiWQhhrh9vGurcMws9otMxWOgI
RbJU52VpzJ0s0DTcZcV6kbU0D8XkTg6TCKoLMiDI/szKhAdcsct47AZTQ3gvW3/T
2SgMPT+vPLCTNArmUvhZ1yP84cF1qRAd4PFtNBdUps8TvDBxpmO3dhnU5/RsnOhv
PK3glG+7dASYziP287qi3ZcAAwcH/RLaYHvNoLda4GjMmpf6Gk8c75d8gT5LRgow
A0HIYgi6gRxkzbYkxYYwO2LwF5LQOAVr4OAdYypQSfl8nyX3gUPwp//TvxvPQxO2
PGE8Pp+mRzAF+AxhqCJvow2ZYNNM7GlTYXqn5MNjQA51ePhuQzLC4qetiCnk96IH
cPP5I25gXKixr0FcaHUtoD7b0Tc4qFEt5zcnI9qBmirfxSxv8oGUt8L3QTYAEIpZ
W15UMTXXBD1Owre/nTCCNa+AJAMPZ9ThuJvyZkQv42iuW88uIdwConwtYMJ6yWwb
6tV1qvsW6wX/kWcw8SH3osgDnoDNidvXAS/STcbVLRjqr+rcZOCITwQYEQIADwUC
TXaE0AIbDAUJA8JnAAAKCRC3phjmiMX6i3buAJ9ReqMXHbSv5dcz5vAOETpQScxh
0gCg29qZO+SVTiCwBzdSbhJQDX2lpCE=
=ecUJ
-----END PGP PUBLIC KEY BLOCK-----',
            ),
            'E4AAA5CC' => array(
                'pub' => '1024D/<a href="%s">E4AAA5CC</a> 2011-03-08 [<span style="color: #800000;">revocada</span>]',
                'uid' => 'Pablo Bangueses (Bng5) &lt;bng5@php.net&gt;',
//                'sub' => '2048g/AFEC021F 2011-03-08 __________ 2013-03-07',
                'fingerprint' => '48D1 776C 4615 56B2 89D6  8C16 56B6 D317 E4AA A5CC',
                'content' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG v1.4.6 (GNU/Linux)

mQGiBE12hCgRBADFxKA5AmTrFJphxcPbAx1k7PvX7aqxjlOqqXCiDCbaIuewsD09
vsaEBfohw5Xnj8IZmyF8ty23c2ZbJ2fdr+VkytwwEy1ZFIqzkhRiFIXU3Z8rZq38
iZ5MiGjoO1RR8mCu5NGZgHL6ThPX2FhR/1yZYNg3WssWsIzxGwM3ig23ewCg9yPi
i92Fg1D6RJTsY+KY5cAUn18EAJ6oEYxo2vRGzyvXogh6JJmgk4rWU0iRmVPVfvy6
V7FC9Val6Frj/AlPDbQGqaxDb+xqJMhQjkgwRXvHTaZqRHwC5ns5+7GYZv9ZwZ1+
kQJHQKgp+nej4jQ6vErXIbheD8Gdg4MZ5WI/ro9kgS+SKE9MPJgX3so7PtvF4DFd
mDumA/4pE5LavHcldtGD4VnvUsDgESt9Ny0GBRjkCoiRNZIRTaKpzp1I95A2dChh
QPJYivyCPiYRSgXSpJg8VE+pk3DT8Ns5/ejxnfOrQvvBUPDu6jNnFfzxk4SQ3QoU
9VVurnegNmIkTicztiBJEUgjjutZAH3e2/bLeU24QQeeQqQgo7QlUGFibG8gQmFu
Z3Vlc2VzIChCbmc1KSA8Ym5nNUBwaHAubmV0PohmBBMRAgAmBQJNdoQoAhsDBQkD
wmcABgsJCAcDAgQVAggDBBYCAwECHgECF4AACgkQVrbTF+SqpcwxdgCg6+E+zkGu
Xf7dNzTDX+hLRhhrCnwAnRRIjvoeMZ5RWmBPq8wkCIRMy9druQINBE12hDMQCAC3
I9SE6R27AAeDSa1tdB7UsUnMaSJOTG8hk5PyLsWNulEyg2NpivSniTjY1/jX9+BS
/n53tjbdQx395tVaiMekCacag5I1mPM6YkCzXothGaQP4LidFDs655J0JrifIYec
rLnCaWL5XIyXvdoSnLXVCme5sJXqbqaeoNp2LrtX+dYsaIJbEo2yELy2JjNssdaP
0MXwtOAGOYJTOTWFVrM7WRaXolzRU/TC0CIjgouSarNeZxJwwFxZcKk47jKf9o2/
INGoUyiSmiD8Q0K92WgtwYmKdHrlM+VEcCxs2XlRhtI4YwBdM8o+pLB5vWqECv6G
fI/k6vqUT2KR7PixW5trAAMFCACRNqDSoMSTWt92xz/D18fEPjbySlDI5Ld1I35N
Zlppho44u/g02Zxp+zjJFmCzB81C5XXxUZokeTSKs1Thj30Jxl4c2eiyKTzklLq+
0tcHQNBuDEdT5IekjMugjpQnV6OknOtlmy9JH1ZknGvz9R0z1TqKgtot+aaHzTlt
cihTAmx9WLRpyaVCHihYQHpEwX0wTdC+Hv7kXxbaF3vwkMr1KoW748xFFSTIuai8
mmjDJ6PtyI9kefrqCBcVderEbIyTHhphGBQp4Feo6w5NEV+kgZbs8M0lq4yM1FpC
s/EQhLS8K72Il3xSqeHCbeA6IH49hU1p0loLg/kaRQzdYIdBiE8EGBECAA8FAk12
hDMCGwwFCQPCZwAACgkQVrbTF+SqpcwFrgCgpZJ73NawXfO4jF3qtebv9Wmm38AA
n17GS8caGgAJJICURZPHDHQMSFmp
=LqTr
-----END PGP PUBLIC KEY BLOCK-----',
            ),
        );
        
    
    public function __construct($path = null) {
        parent::__construct($path);
        //$this->prefix = (strpos($_SERVER['HTTP_HOST'], $this->action->controller->id.'.') === 0) 
        $this->prefix = ($_SERVER['HTTP_HOST'] == 'pablo.bng5.net') 
            ? 
            '' : 
            'pablo/';

        array_unshift($this->pageTitle, 'Pablo Bangueses');
    }


    public function actionIndex() {

        if(empty($this->prefix)) {
            //Yii::app()->clientScript->registerLinkTag('openid.server', null, 'http://bng5.net/openid/server');
            //Yii::app()->clientScript->registerLinkTag('openid.delegate', null, 'http://pablo.bng5.net');            
            Yii::app()->clientScript->registerLinkTag('openid2.provider', null, 'https://www.google.com/accounts/o8/ud?source=profiles');
            Yii::app()->clientScript->registerLinkTag('openid2.local_id', null, 'https://profiles.google.com/pablobngs');

        }
        $datetime1 = new DateTime('1981-02-24');
        $datetime2 = new DateTime();
        $anyos = floor(($datetime2->format('U') - $datetime1->format('U')) / 31570560);
        // $interval = $datetime1->diff($datetime2);
        // $interval->format('%Y'),
		$this->render('index', array(
            'edad' => $anyos,
            'keys' => $this->keys,
            'ssh_keys' => $this->ssh_keys,
        ));
	}
    
	public function actionGnupg() {
        
        if(!array_key_exists('id', $this->actionParams)) {
            $breadcrumbs = array(
                'GnuPG'
            );
            $this->breadcrumbs = empty($this->prefix) ? $breadcrumbs : array_merge(array('Sobre mi' => '/pablo'), $breadcrumbs);
            $this->render('gnupg/index');
            return;
        }
        $id = $this->actionParams['id'];
        $download = false;
        if(preg_match('/\.(asc)$/', $id)) {
            $id = preg_replace('/\.(asc)$/', '', $id);
            $download = true;
        }
        if(!array_key_exists($id, $this->keys)) {
            throw new CHttpException(404);
        }
        if($download) {
            header("Content-Type: application/pgp-keys");
            echo $this->keys[$id]['content'];
            return;
        }
        
        $breadcrumbs = array(
            'GnuPG' => '/'.$this->prefix.'gnupg',
            $id,
        );
        $this->breadcrumbs = empty($this->prefix) ? $breadcrumbs : array_merge(array('Sobre mi' => '/pablo'), $breadcrumbs);
        $this->render('gnupg/key', array(
            'id' => $id,
            'key' => $this->keys[$id],
        ));
	}

	public function actionSsh_keys() {
        if(!array_key_exists('id', $this->actionParams)) {
            $breadcrumbs = array(
                'Llaves SSH'
            );
            $this->breadcrumbs = empty($this->prefix) ? $breadcrumbs : array_merge(array('Sobre mi' => '/pablo'), $breadcrumbs);
            $this->render('ssh_key/index');
            return;
        }
        $id = $this->actionParams['id'];
        $download = false;
        if(preg_match('/\.(pub)$/', $id)) {
            $id = preg_replace('/\.(pub)$/', '', $id);
            $download = true;
        }
        if(!array_key_exists($id, $this->ssh_keys)) {
            throw new CHttpException(404);
        }
        if($download) {
            header("Content-Type: text/plain");
            echo $this->ssh_keys[$id]['content'];
            return;
        }

        $breadcrumbs = array(
            'SSH Keys' => '/'.$this->prefix.'ssh_keys',
            $this->ssh_keys[$id]['id'],
        );
        $this->breadcrumbs = empty($this->prefix) ? $breadcrumbs : array_merge(array('Sobre mi' => '/pablo'), $breadcrumbs);
        $this->render('ssh_key/key', array(
            'id' => $id,
        ));
    }
	public function actionTimeline() {
//        $this->layout = '//pablo/timelinelayout';

        $this->render('timeline');
    }
}