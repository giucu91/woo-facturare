=== Facturare WooCommerce ===
Contributors: giucu91
Tags: woocommerce, facturare, persoana fizica, persoana juridica
Requires at least: 3.5
Tested up to: 5.3
Stable tag: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Datorită legislației în vigoare din România fiecare magazin online trebuie să aibă anumite informații pe factură. Acest modul vine în ajutorul tau si te ajuta sa adaugi respectivele informații mai ușor.

Pentru persoanele fizice poti aduaga:
	- CNP

Pentru persoanele juridice poti adauga:
	- CUI
	- Nr. Registru Comertului
	- Nume Banca
	- IBAN

Acest plugin nu emite facturi, el doar adauga campurile necesare unei facturi pe pagina de checkout.

Pentru mai multe informatii vizitati [Modul Facturare](http://georgeciobanu.com/facturare/)

== Installation ==

Please see [Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins) in the WordPress Codex.

Dupa activare, du-te la *WooCommerce > Settings > Facturare*

== Frequently Asked Questions ==

= Cum iau campurile din baza de date ? =

Pentru a optimiza baza de date, campurile adaugate de acest plugin sunt salvate doar intr-un singur rand din baza de date in loc de 5. In tabela wp_postmeta, avand key-ul 'av_facturare'. Din cauza asta nu este usoara preluarea din baza de date informatii, asa ca am facut urmatoarele key-uri, care nu se gasesc in baza de date, dar o sa va returneze valoarea dorita:

`$cnp = get_post_meta( $order_id, '_av_facturare_cnp', true );`
`$nr_reg_com = get_post_meta( $order_id, '_av_facturare_nr_reg_com', true );`
`$cui = get_post_meta( $order_id, '_av_facturare_cui', true );`
`$nume_banca = get_post_meta( $order_id, '_av_facturare_nume_banca', true );`
`$iban = get_post_meta( $order_id, '_av_facturare_iban', true );`

== Changelog ==

** 1.0.6 **
- Integrated with checkout fields editor plugins ( Checkout Field Editor for WooCommerce / Flexible Checkout Fields )
- Added function to get our info from db.

** 1.0.5 **
- Added feedback form
- Fixed incompatibility with CartFlow

** 1.0.4 **
- Added return in 'woocommerce_get_settings_pages' filter.
- Added compatibility with WooCommerce Admin

** 1.0.3 **
- Fixed undefined index.

** 1.0.2 **
- Fixed optional/required in translated WooCommerce.

** 1.0.1 **
- Fixed error on orders admin page.

** 1.0 **
- Initial release