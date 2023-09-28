<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id')->nullable()->comment('ユーザーID');
            $table->string('orderNumber')->nullable()->comment('注文番号');
            $table->integer('orderProgress')->nullable()->comment('ステータス');
            $table->integer('subStatusId')->nullable()->comment('サブステータスID');
            $table->string('subStatusName')->nullable()->comment('サブステータス');
            $table->string('orderDatetime')->nullable()->comment('注文日時');
            
            $table->date('orderDate')->nullable()->comment('注文日');

            $table->string('shopOrderCfmDatetime')->nullable()->comment('注文確認日時');
            $table->string('orderFixDatetime')->nullable()->comment('注文確定日時');
            $table->string('shippingInstDatetime')->nullable()->comment('発送指示日時');
            $table->string('shippingCmplRptDatetime')->nullable()->comment('発送完了報告日時');
            $table->date('cancelDueDate')->nullable()->comment('キャンセル期限日');
            $table->date('deliveryDate')->nullable()->comment('お届け日指定');
            $table->integer('shippingTerm')->nullable()->comment('お届け時間帯');
            $table->text('remarks')->nullable()->comment('コメント');
            $table->integer('giftCheckFlag')->nullable()->comment('ギフト配送希望フラグ');
            $table->integer('severalSenderFlag')->nullable()->comment('複数送付先フラグ');
            $table->integer('equalSenderFlag')->nullable()->comment('送付先一致フラグ');
            $table->integer('isolatedIslandFlag')->nullable()->comment('離島フラグ');
            $table->integer('rakutenMemberFlag')->nullable()->comment('楽天会員フラグ');
            $table->integer('carrierCode')->nullable()->comment('利用端末');
            $table->integer('emailCarrierCode')->nullable()->comment('メールキャリアコード');
            $table->integer('orderType')->nullable()->comment('注文種別');
            $table->string('reserveNumber')->nullable()->comment('申込番号');
            $table->integer('reserveDeliveryCount')->nullable()->comment('申込お届け回数');
            $table->integer('cautionDisplayType')->nullable()->comment('警告表示タイプ');
            $table->integer('cautionDisplayDetailType')->nullable()->comment('警告表示タイプ詳細');
            $table->integer('rakutenConfirmFlag')->nullable()->comment('楽天確認中フラグ');
            $table->integer('goodsPrice')->nullable()->comment('商品合計金額');
            $table->integer('goodsTax')->nullable()->comment('外税合計');
            $table->integer('postagePrice')->nullable()->comment('送料合計');
            $table->integer('deliveryPrice')->nullable()->comment('代引料合計');
            $table->integer('paymentCharge')->nullable()->comment('決済手数料合計');
            $table->integer('paymentChargeTaxRate')->nullable()->comment('決済手続税率');
            $table->integer('totalPrice')->nullable()->comment('合計金額');
            $table->integer('requestPrice')->nullable()->comment('請求金額');
            $table->integer('couponAllTotalPrice')->nullable()->comment('クーポン利用総額');
            $table->integer('couponShopPrice')->nullable()->comment('店舗発行クーポン利用額');
            $table->integer('couponOtherPrice')->nullable()->comment('楽天発行クーポン利用額');
            $table->integer('additionalFeeOccurAmountToUser')->nullable()->comment('注文者負担金合計');
            $table->integer('additionalFeeOccurAmountToShop')->nullable()->comment('店舗負担金合計');
            $table->integer('asurakuFlag')->nullable()->comment('あす楽希望フラグ');
            $table->integer('drugFlag')->nullable()->comment('医薬品受注フラグ');
            $table->integer('dealFlag')->nullable()->comment('楽天スーパーDEAL商品受注フラグ');
            $table->integer('membershipType')->nullable()->comment('メンバーシッププログラム受注タイプ ');
            $table->string('memo')->nullable()->comment('ひとことメモ');
            $table->string('operator')->nullable()->comment('担当者');
            $table->string('mailPlugSentence')->nullable()->comment('メール差込文');
            $table->integer('modifyFlag')->nullable()->comment('購入履歴修正有無フラグ');
            $table->integer('receiptIssueCount')->nullable()->comment('領収書発行回数');
            $table->string('receiptIssueHistoryList')->nullable()->comment('領収書発行履歴リスト');
            
            $table->string('Order_zipCode1')->nullable()->comment('郵便番号1');
            $table->string('Order_zipCode2')->nullable()->comment('郵便番号2');
            $table->string('Order_prefecture')->nullable()->comment('都道府県');
            $table->string('Order_city')->nullable()->comment('郡市区');
            $table->string('Order_subAddress')->nullable()->comment('それ以降の住所');
            $table->string('Order_familyName')->nullable()->comment('姓');
            $table->string('Order_firstName')->nullable()->comment('名');
            $table->string('Order_familyNameKana')->nullable()->comment('姓カナ');
            $table->string('Order_firstNameKana')->nullable()->comment('名カナ');
            $table->string('Order_phoneNumber1')->nullable()->comment('電話番号1');
            $table->string('Order_phoneNumber2')->nullable()->comment('電話番号2');
            $table->string('Order_phoneNumber3')->nullable()->comment('電話番号3');
            $table->string('Order_emailAddress')->nullable()->comment('メールアドレス');
            $table->string('Order_sex')->nullable()->comment('性別');
            $table->integer('Order_birthYear')->nullable()->comment('誕生日(年)');
            $table->integer('Order_birthMonth')->nullable()->comment('誕生日(月)');
            $table->integer('Order_birthDay')->nullable()->comment('誕生日(日)');
            
            $table->integer('settlementMethodCode')->nullable()->comment('支払方法コード');
            $table->string('settlementMethod')->nullable()->comment('支払方法名');
            $table->integer('rpaySettlementFlag')->nullable()->comment('楽天市場の共通決済手段フラグ');
            $table->string('cardName')->nullable()->comment('クレジットカード種類');
            $table->string('cardNumber')->nullable()->comment('クレジットカード番号');
            $table->string('cardOwner')->nullable()->comment('クレジットカード名義人');
            $table->string('cardYm')->nullable()->comment('クレジットカード有効期限');
            $table->integer('cardPayType')->nullable()->comment('クレジットカード支払い方法');
            $table->string('cardInstallmentDesc')->nullable()->comment('クレジットカード支払い回数');
            
            $table->string('deliveryName')->nullable()->comment('配送方法');
            $table->integer('deliveryClass')->nullable()->comment('配送区分');
            $table->integer('usedPoint')->nullable()->comment('ポイント利用額');
            $table->integer('basketId')->nullable()->comment('送付先ID');
            //$table->integer('postagePrice')->nullable()->comment('送料');
            $table->double('postageTaxRate')->nullable()->comment('送料税率');
            //$table->integer('deliveryPrice')->nullable()->comment('代引料');
            $table->double('deliveryTaxRate')->nullable()->comment('代引料税率');
            //$table->integer('goodsTax')->nullable()->comment('送付先外税合計');
            //$table->integer('goodsPrice')->nullable()->comment('商品合計金額');
            //$table->integer('totalPrice')->nullable()->comment('合計金額');
            $table->string('noshi')->nullable()->comment('のし');
            $table->string('defaultDeliveryCompanyCode')->nullable()->comment('購入時配送会社');
            $table->string('packageDeleteFlag')->nullable()->comment('packageDeleteFlag');
            
            $table->string('Sender_zipCode1')->nullable()->comment('郵便番号1');
            $table->string('Sender_zipCode2')->nullable()->comment('郵便番号2');
            $table->string('Sender_prefecture')->nullable()->comment('都道府県');
            $table->string('Sender_city')->nullable()->comment('郡市区');
            $table->string('Sender_subAddress')->nullable()->comment('それ以降の住所');
            $table->string('Sender_familyName')->nullable()->comment('姓');
            $table->string('Sender_firstName')->nullable()->comment('名');
            $table->string('Sender_familyNameKana')->nullable()->comment('姓カナ');
            $table->string('Sender_firstNameKana')->nullable()->comment('名カナ');
            $table->string('Sender_phoneNumber1')->nullable()->comment('電話番号1');
            $table->string('Sender_phoneNumber2')->nullable()->comment('電話番号2');
            $table->string('Sender_phoneNumber3')->nullable()->comment('電話番号3');
            $table->integer('Sender_isolatedIslandFlag')->nullable()->comment('離島フラグ');

            $table->integer('taxRate')->nullable()->comment('税率');
            $table->integer('reqPrice')->nullable()->comment('請求金額');
            $table->integer('reqPriceTax')->nullable()->comment('請求額に対する税額');
            //$table->integer('totalPrice')->nullable()->comment('合計金額');
            //$table->integer('paymentCharge')->nullable()->comment('決済手数料');
            $table->integer('couponPrice')->nullable()->comment('クーポン割引額');
            $table->integer('point')->nullable()->comment('利用ポイント数');

            $table->string('shoppingMallName')->nullable()->comment('モール名');

            $table->string('dateOfShipment')->nullable()->comment('商品発送日');
            $table->string('shippingDocumentNumber')->nullable()->comment('発送伝票番号'); 
            $table->string('emailSentDate')->nullable()->comment('メール送信日');
            $table->string('cmpletionReportUpLoadDate')->nullable()->comment('モールに完了報告アップ日'); 
            $table->string('endFlag')->nullable()->comment('終了フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
