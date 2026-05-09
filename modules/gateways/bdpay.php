<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function bdpay_MetaData()
{
    return [
        'DisplayName' => 'Mobile Banking (bdPay)',
        'APIVersion' => '1.1',
        'DisableLocalCreditCardInput' => true,
        'TokenisedStorage' => false,
    ];
}

function bdpay_config()
{
    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'Mobile Banking (bdPay)',
        ],
        'bkashNumber' => [
            'FriendlyName' => 'bKash Number',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter bKash number',
        ],
        'nagadNumber' => [
            'FriendlyName' => 'Nagad Number',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter Nagad number',
        ],
        'rocketNumber' => [
            'FriendlyName' => 'Rocket Number',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter Rocket number',
        ],
        'adminWhatsApp' => [
            'FriendlyName' => 'Admin WhatsApp Number',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter number with country code (e.g. 88017XXXXXXXX)',
        ],
        'adminEmail' => [
            'FriendlyName' => 'Admin Email Address',
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter email to receive verifications',
        ],
    ];
}

function bdpay_link($params)
{
    $systemUrl  = $params['systemurl'];
    $invoiceId  = $params['invoiceid'];
    $amount     = $params['amount'];
    $currency   = $params['currency'];

    $bkashNumber  = $params['bkashNumber'];
    $nagadNumber  = $params['nagadNumber'];
    $rocketNumber = $params['rocketNumber'];

    $adminWhatsApp = preg_replace('/[^0-9]/', '', $params['adminWhatsApp']);
    $adminEmail    = $params['adminEmail'];

    $css = '<style>
:root{--bk:#e2136e;--ng:#f97316;--rk:#7c3aed;--wa:#16a34a;--em:#4f46e5;--sl:#ffffff;--bd:#f0f0f0;--tx:#575757;--t2:#64748b;--t3:#94a3b8;}
.bdpo{display:none;position:fixed;inset:0;background:rgba(2,6,23,.6);backdrop-filter:blur(8px);z-index:99999;align-items:center;justify-content:center;opacity:0;transition:opacity .3s ease}
.bdpo.on{display:flex;opacity:1}
.bdpc{width:100%;max-width:525px;margin:16px;border-radius:6px;background:#fff;background-clip:padding-box;overflow:hidden;box-shadow:0 0 1px rgba(0,0,0,.1),0 2px 4px rgba(0,0,0,.2);border:0;outline:0;font-size:16px;position:relative;transform:translateY(18px) scale(.98);transition:transform .35s cubic-bezier(.34,1.56,.64,1);max-height:92vh;display:flex;flex-direction:column}
.bdpo.on .bdpc{transform:translateY(0) scale(1)}
.bdph{padding:16px 18px 0;background:#fff;flex-shrink:0}
.bdph-top{display:flex;align-items:center;margin-bottom:14px}
.bdph-logo{height:36px;width:auto;border-radius:8px;object-fit:contain}
.bdph-meta{flex:1}
.bdph-title{font-size:16px;font-weight:700;color:var(--t2);margin:0 0 2px}
.bdph-sub{font-size:14px;color:var(--t2);margin:0;font-weight:500;}
.bdph-close{margin-left:auto;padding:0;background:transparent;border:0;appearance:none;cursor:pointer;font-size:22px;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.2;align-self:flex-start;flex-shrink:0}
.bdph-close:hover{opacity:.5}
.bdp-tabs{display:flex;border-bottom:1.5px dashed var(--bd);}
.bdp-tab{flex:1;padding:9px 8px;border:none;border-bottom:1.5px dashed transparent;background:none;color:var(--t3);font-size:16px;font-weight:600;cursor:pointer;transition:all .2s;margin-bottom:-1.5px}
.bdp-tab:hover{color:var(--t2)}
.bdp-tab[data-t="bkash"].on{color:var(--bk);border-bottom-color:var(--bk)}
.bdp-tab[data-t="nagad"].on{color:var(--ng);border-bottom-color:var(--ng)}
.bdp-tab[data-t="rocket"].on{color:var(--rk);border-bottom-color:var(--rk)}
.bdpb{padding:16px 18px;overflow-y:auto;flex:1;background:var(--sl);scrollbar-width:none}
.bdpb::-webkit-scrollbar{display:none}
.bdpm{display:none}
.bdpm.on{display:block;animation:bdFade .2s ease}
.bdp-btn:focus,.bdp-tab:focus,.bdph-close:focus,button:active,button:focus{outline:0}
@keyframes bdFade{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
.bdp-card{border-radius:10px;padding:14px 16px;background:#fbfbfb;border:1.5px solid var(--bd)}
.bdp-label{font-size:10px;font-weight:700;color:var(--t3);text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px}
.bdp-amount{font-size:22px;font-weight:800;letter-spacing:-.5px;line-height:1.1;margin-bottom:12px}
.bdp-row{padding:8px 0;border-bottom:1.5px solid var(--bd)}.bdp-row:last-child{border-bottom:none}
.bdp-row-label{font-size:11px;color:var(--t2);margin-bottom:4px}
.bdp-numbox{display:flex;align-items:center;gap:8px;background:var(--sl);border-radius:5px;padding:5px 10px;border:1.5px solid var(--bd)}
.bdp-si{margin-top:6px}
.bdp-numtxt{font-weight:700;font-size:13px;color:var(--tx);flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.bdp-copy{border:1px solid var(--bd);padding:5px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:var(--t2);background:transparent;cursor:pointer;transition:all .2s;flex-shrink:0;width:28px;height:28px;outline:none}
.bdp-copy:hover,.bdp-copy:focus{border:1px solid var(--bd);background:transparent;outline:none}
.bdp-copy.ok{color:#16a34a!important}
.bdp-steps{list-style:none;margin:4px 0 0;padding:0;counter-reset:step}
.bdp-steps>li{counter-increment:step;display:flex;gap:10px;align-items:flex-start;padding:3px 0}
.bdp-steps>li::before{content:counter(step);min-width:22px;height:22px;border-radius:50%;background:var(--step-c,#4f46e5);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px}
.bdp-step-body{flex:1;text-align:left}
.bdp-step-text{font-size:14px;color:var(--t2);line-height:1.4;display:block;text-align:left}
.bdp-amt-cur{font-size:11px;font-weight:600;color:var(--t3);margin-left:4px}
.bdp-fg{margin-bottom:12px;position:relative}
.bdp-fg label{display:block;font-size:14px;font-weight:600;color:var(--tx);margin-bottom:5px;text-align:left}
.bdp-input{width:100%;padding:10px 12px 10px 36px;border:1.5px solid var(--bd);border-radius:10px;box-sizing:border-box;font-size:13px;color:var(--tx);background:#fff;transition:all .2s;outline:none}
.bdp-input::placeholder{color:var(--t3)}
.bdp-input.err{border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.1);animation:bdShake .3s ease}
.bdp-ico{position:absolute;left:10px;bottom:14px;color:var(--t3);pointer-events:none;line-height:1}
@keyframes bdShake{0%,100%{transform:translateX(0)}25%,75%{transform:translateX(-4px)}50%{transform:translateX(4px)}}
.bdp-btns{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:14px}
@media(max-width:380px){.bdp-btns{grid-template-columns:1fr}}
.bdp-btn{display:flex;align-items:center;justify-content:center;gap:7px;padding:11px 10px;border:none;border-radius:10px;font-size:14px;font-weight:600;color:#fff;cursor:pointer;transition:all .2s}
.bdp-btn:hover{opacity:.88;transform:translateY(-1px)}
.bdp-btn:active{transform:translateY(0);opacity:1}
.bdp-btn.wa{background:#16a34a}
.bdp-btn.em{background:#4f46e5}
.bdp-foot{text-align:center;font-size:12px;color:var(--t3);margin-top:12px;padding-top:12px;border-top:1.5px dashed var(--bd)}
.bdp-foot strong{color:var(--t2)}

</style>';

    $amt  = htmlspecialchars($amount);
    $cur  = htmlspecialchars($currency);
    $iid  = htmlspecialchars($invoiceId);
    $bkn  = htmlspecialchars($bkashNumber);
    $ngn  = htmlspecialchars($nagadNumber);
    $rkn  = htmlspecialchars($rocketNumber);
    $waN  = htmlspecialchars($adminWhatsApp);
    $emA  = htmlspecialchars($adminEmail);

    $waIcon  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.121 1.532 5.849L.057 23.571a.5.5 0 0 0 .61.637l5.83-1.529A11.945 11.945 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.742 9.742 0 0 1-4.98-1.368l-.356-.213-3.702.97.988-3.606-.234-.374A9.713 9.713 0 0 1 2.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z"/></svg>';
    $emIcon  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>';

    $html = '
<button type="button" class="btn btn-success" id="bdpay-open-modal" style="padding:.25rem .5rem;font-size:.875rem;line-height:1.5;border-radius:.2rem;color:#fff;background-color:#28a745;border-color:#28a745">Pay Now</button>

<div class="bdpo" id="bdpay-modal">
  <div class="bdpc">
    <div class="bdph">
      <div class="bdph-top">
        <div class="bdph-meta">
          <p class="bdph-title">Complete Your Payment</p>
          <p class="bdph-sub">Invoice #'.$iid.'</p>
        </div>
        <button type="button" class="bdph-close" id="bdpay-close-modal">&times;</button>
      </div>
      <div class="bdp-tabs">
        <button class="bdp-tab on" data-t="bkash">bKash</button>
        <button class="bdp-tab" data-t="nagad">Nagad</button>
        <button class="bdp-tab" data-t="rocket">Rocket</button>
      </div>
    </div>

    <div class="bdpb">
      <input type="hidden" id="bdpay-invoice-id" value="'.$iid.'">
      <input type="hidden" id="bdpay-amount" value="'.$amt.' '.$cur.'">
      <input type="hidden" id="bdpay-gateway" value="bkash">

      <!-- bKash -->
      <div id="bdpm-bkash" class="bdpm on">
        <div class="bdp-card">
          <div class="bdp-label" style="margin-bottom:8px">How to Pay</div>
          <ol class="bdp-steps" style="--step-c:var(--bk)">
            <li><div class="bdp-step-body"><span class="bdp-step-text">Open the bKash app or dial *247#</span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Select <strong>Send Money</strong></span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Enter our bKash Number:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt" id="sn-bkash">'.$bkn.'</span><button type="button" class="bdp-copy"  onclick="bdCopy(\'sn-bkash\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Copy and paste the exact amount:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt"><span id="sa-bkash">'.$amt.'</span><span class="bdp-amt-cur">'.$cur.'</span></span><button type="button" class="bdp-copy"  onclick="bdCopy(\'sa-bkash\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Add the invoice number in the Reference:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt" id="sr-bkash">Invoice #'.$iid.'</span><button type="button" class="bdp-copy"  onclick="bdCopy(\'sr-bkash\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Confirm with PIN</span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Tap to pay.</span></div></li>
          </ol>
        </div>
      </div>

      <!-- Nagad -->
      <div id="bdpm-nagad" class="bdpm">
        <div class="bdp-card">
          <div class="bdp-label" style="margin-bottom:8px">How to Pay</div>
          <ol class="bdp-steps" style="--step-c:var(--ng)">
            <li><div class="bdp-step-body"><span class="bdp-step-text">Open the Nagad app or dial *167#</span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Select <strong>Send Money</strong></span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Enter our Nagad Number:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt" id="sn-nagad">'.$ngn.'</span><button type="button" class="bdp-copy" style="" onclick="bdCopy(\'sn-nagad\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Copy and paste the exact amount:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt"><span id="sa-nagad">'.$amt.'</span><span class="bdp-amt-cur">'.$cur.'</span></span><button type="button" class="bdp-copy" style="" onclick="bdCopy(\'sa-nagad\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Add the invoice number in the Reference:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt" id="sr-nagad">Invoice #'.$iid.'</span><button type="button" class="bdp-copy" style="" onclick="bdCopy(\'sr-nagad\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Confirm with PIN</span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Tap to pay.</span></div></li>
          </ol>
        </div>
      </div>

      <!-- Rocket -->
      <div id="bdpm-rocket" class="bdpm">
        <div class="bdp-card">
          <div class="bdp-label" style="margin-bottom:8px">How to Pay</div>
          <ol class="bdp-steps" style="--step-c:var(--rk)">
            <li><div class="bdp-step-body"><span class="bdp-step-text">Open the Rocket app or dial *322#</span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Select <strong>Send Money</strong></span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Enter our Rocket Number:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt" id="sn-rocket">'.$rkn.'</span><button type="button" class="bdp-copy" style="" onclick="bdCopy(\'sn-rocket\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Copy and paste the exact amount:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt"><span id="sa-rocket">'.$amt.'</span><span class="bdp-amt-cur">'.$cur.'</span></span><button type="button" class="bdp-copy" style="" onclick="bdCopy(\'sa-rocket\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Add the invoice number in the Reference:</span><div class="bdp-numbox bdp-si"><span class="bdp-numtxt" id="sr-rocket">Invoice #'.$iid.'</span><button type="button" class="bdp-copy" style="" onclick="bdCopy(\'sr-rocket\',this)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button></div></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Confirm with PIN</span></div></li>
            <li><div class="bdp-step-body"><span class="bdp-step-text">Tap to pay.</span></div></li>
          </ol>
        </div>
      </div>

      <div class="bdp-card" style="margin-top:16px">
        <div class="bdp-label" style="margin-bottom:12px">Your Payment Details</div>
        <div class="bdp-fg">
          <label for="bdpay-sender">Account Number:</label>
          <span class="bdp-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.06 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 17z"/></svg></span>
          <input type="text" id="bdpay-sender" class="bdp-input" placeholder="+88017xxxxxxxx">
        </div>
        <div class="bdp-fg" style="margin-bottom:0">
          <label for="bdpay-txid">Transaction ID (TxID):</label>
          <span class="bdp-ico"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg></span>
          <input type="text" id="bdpay-txid" class="bdp-input" placeholder="9BQ9366TWD">
        </div>
      </div>

      <div class="bdp-btns">
        <button type="button" class="bdp-btn wa" onclick="bdVerify(\'whatsapp\')">'.$waIcon.' Verify via WhatsApp</button>
        <button type="button" class="bdp-btn em" onclick="bdVerify(\'email\')">'.$emIcon.' Verify via Email</button>
      </div>

    <div class="bdp-foot">We&#39;ll verify your payment and notify you soon.</div>
    </div>
  </div>
</div>

<script>
(function(){
  var modal=document.getElementById("bdpay-modal");
  document.getElementById("bdpay-open-modal").onclick=function(){
    modal.style.display="flex";
    setTimeout(function(){modal.classList.add("on");},10);
  };
  function bdClose(){
    modal.classList.remove("on");
    setTimeout(function(){modal.style.display="none";},350);
  }
  document.getElementById("bdpay-close-modal").onclick=bdClose;
  modal.onclick=function(e){if(e.target===modal)bdClose();};  

  var tabs=document.querySelectorAll(".bdp-tab");
  var panels=document.querySelectorAll(".bdpm");
  var gw=document.getElementById("bdpay-gateway");
  tabs.forEach(function(t){
    t.onclick=function(){
      tabs.forEach(function(x){x.classList.remove("on");});
      panels.forEach(function(p){p.classList.remove("on");});
      t.classList.add("on");
      var id=t.getAttribute("data-t");
      document.getElementById("bdpm-"+id).classList.add("on");
      gw.value=id;
    };
  });

  window.bdCopy=function(elId,btn){
    var txt=document.getElementById(elId).innerText;
    navigator.clipboard.writeText(txt).then(function(){
      var old=btn.innerHTML;
      btn.innerHTML=\'&#10003;\';
      btn.classList.add("ok");
      setTimeout(function(){btn.innerHTML=old;btn.classList.remove("ok");},2000);
    });
  };

  window.bdVerify=function(method){
    var inv=document.getElementById("bdpay-invoice-id").value;
    var amt=document.getElementById("bdpay-amount").value;
    var gw=document.getElementById("bdpay-gateway").value;
    var sndEl=document.getElementById("bdpay-sender");
    var txEl=document.getElementById("bdpay-txid");
    var snd=sndEl.value.trim();
    var txid=txEl.value.trim();
    [sndEl,txEl].forEach(function(el){el.classList.remove("err");});
    if(!snd||!txid){
      if(!snd){sndEl.classList.add("err");setTimeout(function(){sndEl.classList.remove("err");},600);}
      if(!txid){txEl.classList.add("err");setTimeout(function(){txEl.classList.remove("err");},600);}
      return;
    }
    var gwName=gw.charAt(0).toUpperCase()+gw.slice(1);
    var msg="Payment Verification\nInvoice: #"+inv+"\nGateway: "+gwName+"\nAmount: "+amt+"\nSender: "+snd+"\nTxID: "+txid;
    if(method==="whatsapp"){
      var wa="'.$waN.'";
      if(!wa){alert("Admin WhatsApp not configured.");return;}
      window.open("https://wa.me/"+wa+"?text="+encodeURIComponent(msg),"_blank");
    } else {
      var em="'.$emA.'";
      if(!em){alert("Admin Email not configured.");return;}
      var a=document.createElement("a");
      a.href="mailto:"+em+"?subject="+encodeURIComponent("Payment Verification #"+inv)+"&body="+encodeURIComponent(msg);
      a.target="_blank";a.rel="noopener noreferrer";
      document.body.appendChild(a);a.click();document.body.removeChild(a);
    }
  };
})();
</script>';

    return $css . $html;
}
