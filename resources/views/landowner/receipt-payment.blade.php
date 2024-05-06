@extends('layouts.print-app')

@section('content')

    <div class="printarea">
        <div class="print-title">
            <div class="receipt">
                <div class="receipt-header">
                    RECEIPT
                </div>
                <div class="receipt-body">
                    <div> {{ $data->bhouse_name }}</div>
                    <div>{{ $data->street }}, {{ $data->brgyDesc }}, {{ $data->citymunDesc }}</div>

                    <div style="margin-top: 25px;">OFFICIAL RECEIPT</div>

                    <div style="display: flex; margin-top: 25px;">
                        <div style="margin-left: auto;">
                            Date : {{ $dateNow }} Receipt No. {{ $data->payment_detail_id }}
                        </div>

                    </div>
                    <div style="display: flex; text-align: left; margin-top: 25px;">
                        <div>Received from: {{ $data->fname }}, {{ $data->mname }} {{ $data->lname }} the amount of {{ $data->amount_paid }}
                            for payment of boarding house rental.</div>
                    </div>

                    <div class="receipt-table">

                        <table>
                            <tr>
                                <td>Amount to be received</td>
                                <td> {{ $data->rental_price }}</td>
                            </tr>
                            <tr>
                                <td>Amount received</td>
                                <td>{{ $data->amount_paid }}</td>
                            </tr>
                            {{-- <tr>
                                <td>Previous Balance</td>
                                <td>{{ $data-> }} - {{ $data->balance }}</td>
                            </tr> --}}
                            <tr>
                                <td>Total Balance</td>
                                <td><?php echo $data->balance; ?></td>
                            </tr>

                          
                        </table>
                    </div>

                </div><!-- receipt body -->

            </div><!--receipt -->



        </div><!--print-title -->
    </div>
@endsection

