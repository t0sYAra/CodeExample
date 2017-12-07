<?
namespace AntonPavlov\PersonalSite\Base;

class RouteRules
{    
    private static $rules;
    private static $redirectRules;
    private static $forbiddenRules;
    
    public static function getRules()
    {
        self::$rules = [
            [
                'regExp' => '\/?',
                'controller' => 'IndexController',
                'action' => 'indexAction',
                'include' => ''
            ],
            [
                'regExp' => '\/kontakty\/?',
                'controller' => 'ContactsController',
                'action' => 'indexAction',
                'include' => ''
            ],
            [
                'regExp' => '\/poisk\/?',
                'controller' => 'SearchController',
                'action' => 'indexAction',
                'include' => ''
            ],
            [
                'regExp' => '\/blog(?:\/(20[0-9]{2}))?(?:\/tags=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:\/(best))?(?:\/search=([-_\s\%a-z0-9абвгдеёжзийклмнопрстуфхцчшщьыъэюя]+))?(?:(?:\/?)|(?:\/([1-9][0-9]{0,8})-([1-9][0-9]{0,8})\/?)?)',
                'controller' => 'BlogController',
                'action' => 'showBlog',
                'include' => ''
            ],
            [
                'regExp' => '\/(menedzher_internet-proektov|stroitel|pilot|trener|spasatel|specialist_po_fito-svetu|mebelshchik|chlen_pravleniya_tszh|kandidat_ehkonomicheskih_nauk)\/?',
                'controller' => 'EntryController',
                'action' => 'showEntry',
                'include' => ''
            ],
            [
                'regExp' => '\/blog\/([-_0-9a-zA-Z]{2,255})\/?',
                'controller' => 'EntryController',
                'action' => 'showEntry',
                'include' => ''
            ],
            [
                'regExp' => '\/pics\/([01abcd])\/([1-9][0-9]{0,10})\/([1-9][0-9]{0,10}).(?:jpg|gif|png)\/?',
                'controller' => 'PictureController',
                'action' => 'showPic',
                'include' => ''
            ],
            [
                'regExp' => '\/captcha\/?',
                'controller' => 'CaptchaController',
                'action' => 'generateCaptcha',
                'include' => ''
            ]
        ];
        return self::$rules;
    }

    public static function redirectRules()
    {
        self::$redirectRules = [
            [
                'from' => '\/blog\/antientropiya_kota\/?',
                'to' => 'blog/antiehntropiya_kota'
            ],
            [
                'from' => '\/blog\/ascimmetriya_informacii_po_kachestvu_v_internete\/?',
                'to' => 'blog/asimmetriya_informacii_po_kachestvu_v_internete'
            ],
            [
                'from' => '\/blog\/aska_ustanovka_i_nastroika\/?',
                'to' => 'blog/aska_ustanovka_i_nastrojka'
            ],
            [
                'from' => '\/blog\/bag_v_obschestve\/?',
                'to' => 'blog/bag_v_obshchestve'
            ],
            [
                'from' => '\/blog\/bam08_1\/?',
                'to' => 'blog/bam_zimoj_rasskaz_pervyj_piter_moskva_habarovsk'
            ],
            [
                'from' => '\/blog\/bam08_10\/?',
                'to' => 'blog/poslednie_kilometry_bama_krasnoyarsk'
            ],
            [
                'from' => '\/blog\/bam08_11\/?',
                'to' => 'blog/novosibirsk_dvazhdy_ordenonosnyj_centr_sibiri'
            ],
            [
                'from' => '\/blog\/bam08_2\/?',
                'to' => 'blog/zimnij_bam_habarovsk_gorod_i_vokzal'
            ],
            [
                'from' => '\/blog\/bam08_3\/?',
                'to' => 'blog/lovkij_gorod_komsomolsk-na-amure_muzej_sssr_pod_otkrytym_nebom_i_pervaya_tochka_na_bame'
            ],
            [
                'from' => '\/blog\/bam08_4\/?',
                'to' => 'blog/vostochnyj_bam_komsomolsk_urgal_alonka_fevralsk_verhnezejsk_tynda'
            ],
            [
                'from' => '\/blog\/bam08_5\/?',
                'to' => 'blog/vostochnyj_bam_glazami_molodoj_ehkimchanki'
            ],
            [
                'from' => '\/blog\/bam08_6\/?',
                'to' => 'blog/stolica_bama_tynda_tyndinskij_muzej_istorii_bama'
            ],
            [
                'from' => '\/blog\/bam08_7\/?',
                'to' => 'blog/tynda_kuvykta_larba_lopcha_chilchi_hani_taksimo'
            ],
            [
                'from' => '\/blog\/bam08_8\/?',
                'to' => 'blog/severomujskij_obhod'
            ],
            [
                'from' => '\/blog\/bam08_9\/?',
                'to' => 'blog/severobajkalsk_ozero_bajkal_muzej_bama'
            ],
            [
                'from' => '\/blog\/bez_nazvaniya\/?',
                'to' => 'blog/prosto_zapis'
            ],
            [
                'from' => '\/blog\/birzha_saitov\/?',
                'to' => 'blog/birzha_sajtov'
            ],
            [
                'from' => '\/blog\/birzha_saitov_dolzhna_sdelat_rynok_civilizovannym\/?',
                'to' => 'blog/birzha_sajtov_dolzhna_sdelat_rynok_civilizovannym'
            ],
            [
                'from' => '\/blog\/buduschee_ineta\/?',
                'to' => 'blog/budushchee_ineta'
            ],
            [
                'from' => '\/blog\/chehiya_2000\/?',
                'to' => 'blog/chekhiya_2000'
            ],
            [
                'from' => '\/blog\/dam-la-ma\/?',
                'to' => 'blog/dam-lya-ma'
            ],
            [
                'from' => '\/blog\/elki_onlain\/?',
                'to' => 'blog/yolki_onlajn'
            ],
            [
                'from' => '\/blog\/festival_feierverkov\/?',
                'to' => 'blog/festival_fejerverkov_ehto_nuzhno_bylo_videt'
            ],
            [
                'from' => '\/blog\/godovschina\/?',
                'to' => 'blog/godovshchina'
            ],
            [
                'from' => '\/blog\/individualizm_kak_atribut_cheloveka\/?',
                'to' => 'blog/individualizm_kak_attribut_cheloveka'
            ],
            [
                'from' => '\/blog\/intervju_sdelai_vybor\/?',
                'to' => 'blog/intervyu_gazete_sdelaj_vybor'
            ],
            [
                'from' => '\/blog\/ja_v_tvittere\/?',
                'to' => 'blog/ya_v_tvittere'
            ],
            [
                'from' => '\/blog\/justsay\/?',
                'to' => 'blog/socialnaya_set_novyh_znakomstv_justsayru'
            ],
            [
                'from' => '\/blog\/justsay_na_poljakh\/?',
                'to' => 'blog/justsay_na_polyah'
            ],
            [
                'from' => '\/blog\/kak_ja_letal_v_grecii\/?',
                'to' => 'blog/kak_ya_letal_v_grecii'
            ],
            [
                'from' => '\/blog\/kak_pogibajut_samolety\/?',
                'to' => 'blog/kak_pogibayut_samolyoty'
            ],
            [
                'from' => '\/blog\/karera\/?',
                'to' => 'blog/karera_rezyume'
            ],
            [
                'from' => '\/blog\/kkg_chetvertaya_stupen\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_chetvyortaya_stupen'
            ],
            [
                'from' => '\/blog\/kkg_devyataya_stupen_sozdanie_i_raskrutka_saita\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_devyataya_stupen_sozdanie_i_raskrutka_sajta'
            ],
            [
                'from' => '\/blog\/kkg_pervaya_stupen_vvedenie\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_vvedenie'
            ],
            [
                'from' => '\/blog\/kkg_pyataya_stupen\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_pyataya_stupen'
            ],
            [
                'from' => '\/blog\/kkg_sedmaya_stupen\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_sedmaya_stupen'
            ],
            [
                'from' => '\/blog\/kkg_shestaya_stupen\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_shestaya_stupen'
            ],
            [
                'from' => '\/blog\/kkg_tretya_stupen\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_tretya_stupen'
            ],
            [
                'from' => '\/blog\/kkg_vosmaya_stupen\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_vosmaya_stupen'
            ],
            [
                'from' => '\/blog\/kkg_vtoraya_stupen_nachinaem_s_nulya\/?',
                'to' => 'blog/kursy_kompyuternoj_gramotnosti_nachinaem_s_nulya'
            ],
            [
                'from' => '\/blog\/koncepciya_innovacionnoi_sistemy_predriyatiya\/?',
                'to' => 'blog/koncepciya_innovacionnoj_sistemy_predpriyatiya'
            ],
            [
                'from' => '\/blog\/konferencija_social_media\/?',
                'to' => 'blog/konferenciya_social_media'
            ],
            [
                'from' => '\/blog\/konferenciya_yandexa\/?',
                'to' => 'blog/konferenciya_yandeksa'
            ],
            [
                'from' => '\/blog\/kto_takojj_anton_pavlov\/?',
                'to' => 'blog/kto_takoj_anton_pavlov'
            ],
            [
                'from' => '\/blog\/mini_hokkei\/?',
                'to' => 'blog/mini-hokkej'
            ],
            [
                'from' => '\/blog\/mir_menya_ljubit\/?',
                'to' => 'blog/mir_menya_lyubit'
            ],
            [
                'from' => '\/blog\/mnogo_pafosa_vokrug_namereniya\/?',
                'to' => 'blog/mnogo_pafosa_vokrug_namereniya_filmy_sekret_i_sila_mysli_sekret-2'
            ],
            [
                'from' => '\/blog\/moi_fokti\/?',
                'to' => 'blog/moi_fotki'
            ],
            [
                'from' => '\/blog\/moi_pervyi_polet_na_yake\/?',
                'to' => 'blog/moj_pervyj_polyot_na_yake'
            ],
            [
                'from' => '\/blog\/mylo-en_soap\/?',
                'to' => 'blog/mylo_en_soap'
            ],
            [
                'from' => '\/blog\/neobhodimost_razrabotki_korporativnoi_innovacionno...\/?',
                'to' => 'blog/neobhodimost_razrabotki_korporativnoj_innovacionnoj_sistemy'
            ],
            [
                'from' => '\/blog\/ne_perevelis_esche_lamushinochki\/?',
                'to' => 'blog/ne_perevelis_eshchyo_lamushinochki_'
            ],
            [
                'from' => '\/blog\/nochnoi_i_dnevnoi_dozory\/?',
                'to' => 'blog/nochnoj_i_dnevnoj_dozory'
            ],
            [
                'from' => '\/blog\/obnovilas_birzha_saitov\/?',
                'to' => 'blog/obnovilas_birzha_sajtov'
            ],
            [
                'from' => '\/blog\/odin_raz_v_god_kozu_ebut\/?',
                'to' => 'blog/odin_raz_v_god_kozu_eut'
            ],
            [
                'from' => '\/blog\/pashalnoe_nastroenie\/?',
                'to' => 'blog/paskhalnoe_nastroenie'
            ],
            [
                'from' => '\/blog\/pereezd_sajjtov_s_narod_na_ucoz\/?',
                'to' => 'blog/pereezd_sajtov_s_narod_na_ucoz'
            ],
            [
                'from' => '\/blog\/perehod_k_noveishim_upravlencheskim_tehnologiyam\/?',
                'to' => 'blog/perekhod_k_novejshim_upravlencheskim_tekhnologiyam_zalog_konkurentosposobnosti_kompanii'
            ],
            [
                'from' => '\/blog\/pirog_osetinskii\/?',
                'to' => 'blog/pirog_osetinskij'
            ],
            [
                'from' => '\/blog\/pobyval_na_kursakh_po_bezopasnomu_obrashheniju_s_o...\/?',
                'to' => 'blog/pobyval_na_kursah_po_bezopasnomu_obrashcheniyu_s_oruzhiem'
            ],
            [
                'from' => '\/blog\/pochitatelyam_moego_talanta\/?',
                'to' => 'blog/pochitatelyam_moego_eheheh_talanta_'
            ],
            [
                'from' => '\/blog\/podgotovka_k_poljotu\/?',
                'to' => 'blog/podgotovka_k_polyotu'
            ],
            [
                'from' => '\/blog\/poiskovye_zaprosy-smeh_skvoz_slezy\/?',
                'to' => 'blog/poiskovye_zaprosy_smekh_skvoz_slyozy'
            ],
            [
                'from' => '\/blog\/pokopavsya_na_spbclub\/?',
                'to' => 'blog/pokopavsya_na_spbclubru_'
            ],
            [
                'from' => '\/blog\/pomogite_napisat_kandidatskuju\/?',
                'to' => 'blog/pomogite_napisat_kandidatskuyu'
            ],
            [
                'from' => '\/blog\/popytka_obosnovaniya_pilotiruemogo_poleta_na_mars\/?',
                'to' => 'blog/popytka_obosnovaniya_pilotiruemogo_polyota_na_mars'
            ],
            [
                'from' => '\/blog\/posobie_po_povysheniju_stoimosti_internet-proekta\/?',
                'to' => 'blog/posobie_po_povysheniyu_stoimosti_internet-proekta'
            ],
            [
                'from' => '\/blog\/prityagivanie_sobytii\/?',
                'to' => 'blog/prityagivanie_sobytij_v_svoyu_zhizn_obyasnenie_cherez_dekogerenciyu_kvantovyh_nelokalnostej'
            ],
            [
                'from' => '\/blog\/privez_kuchu_gribov\/?',
                'to' => 'blog/privyoz_kuchu_gribov'
            ],
            [
                'from' => '\/blog\/prodvinutaja_gazeta\/?',
                'to' => 'blog/prodvinutaya_gazeta'
            ],
            [
                'from' => '\/blog\/pro_potaninskii_konkurs\/?',
                'to' => 'blog/pro_potaninskij_konkurs'
            ],
            [
                'from' => '\/blog\/reiting_doveriya_raschet_na_primere_birzhi_saitov\/?',
                'to' => 'blog/rejting_doveriya_raschyot_na_primere_birzhi_sajtov'
            ],
            [
                'from' => '\/blog\/rekomenduju_komediju_chetyre_lva\/?',
                'to' => 'blog/rekomenduyu_komediyu_chetyre_lva'
            ],
            [
                'from' => '\/blog\/rezervy_uvelicheniya_pribyli_proektone_upravlenie_...\/?',
                'to' => 'blog/rezervy_uvelicheniya_pribyli_proektnoe_upravlenie_moda_iz-za_rubezha'
            ],
            [
                'from' => '\/blog\/rozhdenie_popsovogo_interneta\/?',
                'to' => 'blog/rozhdenie_popsovogo_interneta_chto_nam_dast_veb_20'
            ],
            [
                'from' => '\/blog\/rynki_v_seti_internet_yavnye_i_neyavnye_osobennost...\/?',
                'to' => 'blog/rynki_v_seti_internet_yavnye_i_neyavnye_osobennosti'
            ],
            [
                'from' => '\/blog\/sait_derevni_ivanovo\/?',
                'to' => 'blog/otkryl_sajt_derevni_ivanovo'
            ],
            [
                'from' => '\/blog\/sait_derevni_ivanovo\/?',
                'to' => 'blog/sajt_derevni_ivanovo'
            ],
            [
                'from' => '\/blog\/sait_ocenivaetsya_za_udobstvo_i_krasotu\/?',
                'to' => 'blog/sajt_ocenivaetsya_za_udobstvo_i_krasotu'
            ],
            [
                'from' => '\/blog\/setevaja_ehkonomika_programma_kursa\/?',
                'to' => 'blog/setevaya_ehkonomika_programma_kursa'
            ],
            [
                'from' => '\/blog\/set_internet_kak_vazhnyi_resurs_upravleniya\/?',
                'to' => 'blog/set_internet_kak_vazhnyj_resurs_upravleniya'
            ],
            [
                'from' => '\/blog\/s_dnem_znanii_vas\/?',
                'to' => 'blog/s_dnyom_znanij_vas'
            ],
            [
                'from' => '\/blog\/tehosmotr\/?',
                'to' => 'blog/tekhosmotr'
            ],
            [
                'from' => '\/blog\/trenirovka_po_pervojj_pomoshhi\/?',
                'to' => 'blog/trenirovka_po_pervoj_pomoshchi'
            ],
            [
                'from' => '\/blog\/uchastvoval_v_uchenijakh_spasatelejj_ehkstremum-20...\/?',
                'to' => 'blog/uchastvoval_v_ucheniyah_spasatelej_ehkstremum-2012'
            ],
            [
                'from' => '\/blog\/uchenija_spasatelejj-2013\/?',
                'to' => 'blog/ucheniya_spasatelej-2013'
            ],
            [
                'from' => '\/blog\/uvljoksja_astronomiejj\/?',
                'to' => 'blog/uvlyoksya_astronomiej'
            ],
            [
                'from' => '\/blog\/vchera_pobyval_v_dymokamere_na_kursakh_spasatelejj...\/?',
                'to' => 'blog/vchera_pobyval_v_dymokamere_na_kursah_spasatelej_mchs'
            ],
            [
                'from' => '\/blog\/vernem_sebe_krym\/?',
                'to' => 'blog/vernyom_sebe_krym'
            ],
            [
                'from' => '\/blog\/virtualnyi_reis_simferopol_afiny\/?',
                'to' => 'blog/virtualnyj_rejs_simferopol_afiny'
            ],
            [
                'from' => '\/blog\/viryualnyi_polet_po_krugu\/?',
                'to' => 'blog/virtualnyj_polyot_po_krugu'
            ],
            [
                'from' => '\/blog\/vse_i_u_nas_poshli_griby_ura\/?',
                'to' => 'blog/vsyo_i_u_nas_poshli_griby_ura'
            ],
            [
                'from' => '\/blog\/vtoroi_den_rozhdeniya\/?',
                'to' => 'blog/vtoroj_den_rozhdeniya'
            ],
            [
                'from' => '\/blog\/web-manager_skupil_bilety\/?',
                'to' => 'blog/web-menedzher_skupil_bilety'
            ],
            [
                'from' => '\/blog\/web_2_0_postroenie_reitinga_doveriya_na_osnove_soc...\/?',
                'to' => 'blog/postroenie_rejtinga_doveriya_na_osnove_socialnyh_setej'
            ],
            [
                'from' => '\/blog\/yandex_opyat_jumorit\/?',
                'to' => 'blog/yandeks_opyat_yumorit'
            ],
            [
                'from' => '\/blog\/ya_v_efire_russkoi_sluzhby_BBC\/?',
                'to' => 'blog/ya_v_ehfire_russkoj_sluzhby_bbc'
            ],
            [
                'from' => '\/blog\/zagadochnost_zhenschin_i_moi_formalizm\/?',
                'to' => 'blog/zagadochnost_zhenshchin_i_moj_formalizm'
            ],
            [
                'from' => '\/blog\/zelenyi_pegas\/?',
                'to' => 'blog/zelyonyj_pegas'
            ],
            [
                'from' => '\/blog\/mif_ob_internet-zavisimosti\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/moshennichestvo_v_biznese\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/rezervy_uvelicheniya_pribyli_proektnoe_upravlenie_moda_iz-za_rubezha\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/informacionnaya_model_mira\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/blizkoe_mne_mirovozzrenie\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/pomogite_napisat_kandidatskuyu\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/pirog_osetinskij\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/odin_raz_v_god_kozu_eut\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/kto_takoj_anton_pavlov\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/birzha_sajtov\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/zakaz_zheleznodorozhnyh_biletov\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/raspisanie_poezdov_sankt-peterburg\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/prodazha_aviabiletov\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/sajt_derevni_ivanovo\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/prodvinutaya_gazeta\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/druzhbany\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/proekty\/?',
                'to' => 'blog/menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/obrazovanie\/?',
                'to' => 'blog/kandidat_ehkonomicheskih_nauk'
            ],
            [
                'from' => '\/blog\/poiskovye_zaprosy_smekh_skvoz_slyozy\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/moi_fotki\/?',
                'to' => '/'
            ],
            [
                'from' => '\/blog\/dostizheniya_i_nagrady\/?',
                'to' => 'blog/kandidat_ehkonomicheskih_nauk'
            ],
            [
                'from' => '\/blog\/menedzher_internet-proektov\/?',
                'to' => 'menedzher_internet-proektov'
            ],
            [
                'from' => '\/blog\/stroitel\/?',
                'to' => 'stroitel'
            ],
            [
                'from' => '\/blog\/pilot\/?',
                'to' => 'pilot'
            ],
            [
                'from' => '\/blog\/trener\/?',
                'to' => 'trener'
            ],
            [
                'from' => '\/blog\/spasatel\/?',
                'to' => 'spasatel'
            ],
            [
                'from' => '\/blog\/specialist_po_fito-svetu\/?',
                'to' => 'specialist_po_fito-svetu'
            ],
            [
                'from' => '\/blog\/mebelshchik\/?',
                'to' => 'mebelshchik'
            ],
            [
                'from' => '\/blog\/chlen_pravleniya_tszh\/?',
                'to' => 'chlen_pravleniya_tszh'
            ],
            [
                'from' => '\/blog\/kandidat_ehkonomicheskih_nauk\/?',
                'to' => 'kandidat_ehkonomicheskih_nauk'
            ]
        ];
        return self::$redirectRules;
    }
 
    public static function forbiddenRules()
    {
        self::$forbiddenRules = [
            '^.*proc\/self.*$',
            '^.*(http|ftp|telnet)s?.*$',
            '^.*SERVER.*$',
            '^.*DOCUMENT.*$',
            '^.*root.*$',
            '^.*webmaster.*$',
            '^.*etc.*$',
            '^.*script.*$',
            '^.*passwd.*$',
            '^.*act.*$',
            '^.*post.*$',
            '^.*get.*$',
            '^.*select.*$',
            '^.*insert.*$',
            '^.*drop.*$',
            '^.*update.*$',
            '^.*replace.*$',
            '^.*cookie.*$',
            '^.*howtolisten.*$',
            '^.*respon.*$',
            '^.*\.^(jpg|png|gif).*$'
        ];
        return self::$forbiddenRules;
    }
       
}