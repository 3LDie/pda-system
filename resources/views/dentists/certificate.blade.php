<style>
    @page {
        margin: 0px;
    }
    .cert-body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #1f2937;
        text-align: center;
        padding: 30px;
        background-color: #ffffff;
    }
    .outer-border {
        border: 12px double #1e3a8a;
        padding: 25px;
        height: 530px;
    }
    .inner-border {
        border: 2px solid #b45309;
        padding: 30px;
        height: 460px;
    }
    .organization {
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 3px;
        color: #1e3a8a;
        text-transform: uppercase;
        margin-top: 10px;
    }
    .chapter {
        font-size: 13px;
        letter-spacing: 1px;
        color: #4b5563;
        margin-top: 5px;
        margin-bottom: 30px;
    }
    .main-title {
        font-size: 36px;
        font-weight: bold;
        color: #1e3a8a;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .subtitle {
        font-size: 15px;
        font-style: italic;
        color: #6b7280;
        margin-top: 5px;
        margin-bottom: 35px;
    }
    .dentist-name {
        font-size: 30px;
        font-weight: bold;
        color: #111827;
        margin: 20px auto;
        padding-bottom: 5px;
        width: 70%;
        border-bottom: 2px solid #1e3a8a;
        text-transform: uppercase;
    }
    .details-paragraph {
        font-size: 15px;
        line-height: 1.7;
        color: #374151;
        width: 85%;
        margin: 10px auto 40px auto;
    }
    .highlight {
        font-weight: bold;
        color: #1e3a8a;
    }
    .footer-table {
        width: 100%;
        margin-top: 40px;
    }
    .sig-line {
        border-top: 1px solid #4b5563;
        width: 220px;
        margin: 0 auto 5px auto;
    }
    .sig-name {
        font-size: 13px;
        font-weight: bold;
        color: #111827;
    }
    .sig-title {
        font-size: 11px;
        color: #6b7280;
    }
</style>

<div class="cert-body">
    <div class="outer-border">
        <div class="inner-border">
            
            <div class="organization">Philippine Dental Association</div>
            <div class="chapter">Baguio City Chapter Registry</div>
            
            <div class="main-title">Certificate of Good Standing</div>
            <div class="subtitle">This document officially certifies the professional standing of the registry member</div>
            
            <div style="font-size: 14px; color: #374151;">This recognition is proudly presented to:</div>
            <div class="dentist-name">DR. {{ $name }}</div>
            
            <div class="details-paragraph">
                With Registered PRC License Number <span class="highlight">{{ $prc }}</span>, having verified active clinic operations located at <span class="highlight">{{ $clinic }}</span>, who has successfully complied with all administrative requirements, financial contributions, and organizational code of ethics set forth for the active fiscal period block <span class="highlight">FY {{ $fiscal_year }}</span>. Issued this <span class="highlight">{{ $issue_date }}</span>.
            </div>
            
            <table class="footer-table">
                <tr>
                    <td style="width: 50%; text-align: center;">
                        <div class="sig-line"></div>
                        <div class="sig-name">DR. MARIA CLARA, MANALASTAS</div>
                        <div class="sig-title">Chapter President</div>
                    </td>
                    <td style="width: 50%; text-align: center;">
                        <div class="sig-line"></div>
                        <div class="sig-name">DR. JUAN DELA CRUZ, DMD</div>
                        <div class="sig-title">Chapter Secretary</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>