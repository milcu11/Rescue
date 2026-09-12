@extends('layouts.app')
@section('title', 'User Guide')
@section('page-title', 'User Guide')
@section('breadcrumb')
  <li class="breadcrumb-item active">User Guide</li>
@endsection

@php
  $workflows = [
    'super_admin' => [
      'title' => 'Super Admin workflow',
      'summary' => 'Keep the system configured, secure, and accountable for the response team.',
      'steps' => [
        'Start at Dashboard to review active operations, evacuation capacity, distributions, and low-stock items.',
        'Review Audit Trail regularly to confirm important logins and changes are traceable.',
        'Use My Profile to keep your account details current and sign out when finished.',
        'Coordinate with the MDRRMO before changing operational records or access settings.',
      ],
    ],
    'mdrrmo' => [
      'title' => 'MDRRMO workflow',
      'summary' => 'Coordinate incidents, resources, evacuation, relief, donations, and reports.',
      'steps' => [
        'Begin on Dashboard to check active operations, shelter capacity, supplies, and recent activity.',
        'Create or update Evacuation Centers and monitor occupancy during an incident.',
        'Record Emergency Supplies and Distributions so available stock matches field activity.',
        'Process Donations and review Reports & Analytics for status updates and accountability.',
      ],
    ],
    'drrm_officer' => [
      'title' => 'DRRM Officer workflow',
      'summary' => 'Support response coordination and keep field records complete and current.',
      'steps' => [
        'Check Dashboard for active operations, capacity, stock warnings, and recent distributions.',
        'Update Evacuation Centers when capacity, status, or center information changes.',
        'Record received Donations and coordinate Distributions with the response team.',
        'Use Reports & Analytics and Audit Trail to verify that activity is documented.',
      ],
    ],
    'lgu_staff' => [
      'title' => 'LGU Staff workflow',
      'summary' => 'Maintain inventory and support the LGU response operation.',
      'steps' => [
        'Open Emergency Supplies to check available, low-stock, and depleted items.',
        'Add or update inventory as deliveries arrive or supplies are released.',
        'Record each Distribution so the remaining stock stays accurate.',
        'Use Reports & Analytics to print or export the inventory information needed by the team.',
      ],
    ],
    'warehouse_staff' => [
      'title' => 'Warehouse Staff workflow',
      'summary' => 'Keep warehouse quantities accurate and ready for relief distribution.',
      'steps' => [
        'Review Emergency Supplies before preparing a release.',
        'Update quantities and item status immediately after receiving or releasing stock.',
        'Confirm each Distribution includes the correct items and destination.',
        'Use Reports & Analytics to check inventory totals and export a current report.',
      ],
    ],
    'evac_manager' => [
      'title' => 'Evacuation Manager workflow',
      'summary' => 'Keep evacuation center information, capacity, and evacuee check-ins accurate.',
      'steps' => [
        'Open Evacuation Centers to check each center status and available capacity.',
        'Update center details when staffing, contact information, procedures, or capacity changes.',
        'Use a center page to check in evacuees and check them out when they leave.',
        'Coordinate supply needs and relief distributions with the MDRRMO team.',
      ],
    ],
    'evacuation_manager' => [
      'title' => 'Evacuation Manager workflow',
      'summary' => 'Keep evacuation center information, capacity, and evacuee check-ins accurate.',
      'steps' => [
        'Open Evacuation Centers to check each center status and available capacity.',
        'Update center details when staffing, contact information, procedures, or capacity changes.',
        'Use a center page to check in evacuees and check them out when they leave.',
        'Coordinate supply needs and relief distributions with the MDRRMO team.',
      ],
    ],
    'donor' => [
      'title' => 'Donor workflow',
      'summary' => 'Submit donations and follow their progress from the donor portal.',
      'steps' => [
        'Open My Donations to review donations connected to your account.',
        'Choose Make a Donation to submit a new donation and provide accurate contact details.',
        'Use Track a Donation when you need to check a donation using its tracking code.',
        'Complete payment only through the payment page shown for a monetary donation.',
      ],
    ],
    'volunteer' => [
      'title' => 'Volunteer workflow',
      'summary' => 'Your volunteer portal is managed separately by the volunteer team.',
      'steps' => [
        'Contact the volunteer coordinator if you need access or task instructions.',
        'Use My Profile to confirm your account information when it is available.',
        'Do not create operational records under another user role.',
      ],
    ],
    'resident' => [
      'title' => 'Resident workflow',
      'summary' => 'Use the public services to find evacuation information and request assistance.',
      'steps' => [
        'Open Evacuation Centers from the Public section to view center information and map details.',
        'Follow the registration and check-in instructions provided by the evacuation staff.',
        'Contact the response team if your family has an urgent need or emergency concern.',
      ],
    ],
  ];

  $workflow = $workflows[$role] ?? [
    'title' => 'Your workflow',
    'summary' => 'Use the navigation menu to open the tools assigned to your account.',
    'steps' => [
      'Start at Dashboard and review the information available to your role.',
      'Open the relevant operations page from the sidebar before creating or changing a record.',
      'Ask your coordinator if you are unsure which page or status to use.',
    ],
  ];

  $pageInstructions = [
    [
      'title' => 'Dashboard',
      'icon' => 'fas fa-tachometer-alt text-primary',
      'purpose' => 'Use the dashboard as your starting point for the current response picture.',
      'steps' => [
        'Review active operations, evacuation capacity, distributions, donations, and stock warnings.',
        'Open the related page from the sidebar when a number or warning needs attention.',
        'Return to the dashboard after updating a record to confirm the summary reflects the change.',
      ],
      'check' => 'Treat the dashboard as a summary. Open the source page before making a decision.',
    ],
    [
      'title' => 'Evacuation Centers',
      'icon' => 'fas fa-home text-danger',
      'purpose' => 'Maintain shelter information and record who is currently staying in each center.',
      'steps' => [
        'Select a center to review its status, capacity, contact details, and current occupancy.',
        'Use Create or Edit to update center information, procedures, and capacity when needed.',
        'Open the center details page to check in an evacuee, then check them out when they leave.',
      ],
      'check' => 'Confirm the center status and available slots before directing a family there.',
    ],
    [
      'title' => 'Emergency Supplies',
      'icon' => 'fas fa-boxes text-warning',
      'purpose' => 'Keep item quantities and stock status accurate for the response team.',
      'steps' => [
        'Search or scan the list to find an item and review its current quantity and status.',
        'Create an item when supplies arrive; edit it when the quantity, location, or status changes.',
        'Use the item details to confirm its history before preparing a release.',
      ],
      'check' => 'Save the received or remaining quantity, not the requested quantity.',
    ],
    [
      'title' => 'Distributions',
      'icon' => 'fas fa-truck text-success',
      'purpose' => 'Record relief items sent to evacuation centers or response operations.',
      'steps' => [
        'Create a distribution after the release is approved and select the destination center.',
        'Add each item and the exact quantity released, then include the responsible person or notes.',
        'Review the distribution details and save it so inventory and reports reflect the release.',
      ],
      'check' => 'Verify the destination and quantities before saving because a distribution affects stock totals.',
    ],
    [
      'title' => 'Donations',
      'icon' => 'fas fa-donate text-info',
      'purpose' => 'Capture donated goods or money and follow their status through receipt and use.',
      'steps' => [
        'Create a donation with the donor details, donation type, items or amount, and contact information.',
        'Open the donation record to update its status when it is received, verified, or distributed.',
        'Use the tracking code when a donor asks for an update or proof of status.',
      ],
      'check' => 'Use the correct donation type and preserve the tracking code for follow-up.',
    ],
    [
      'title' => 'Payment History',
      'icon' => 'fas fa-money-bill-wave text-success',
      'purpose' => 'Review payment records connected to monetary donations.',
      'steps' => [
        'Open a payment entry to compare the donor, amount, donation, and payment status.',
        'Use the linked donation record when the payment needs operational follow-up.',
        'Do not mark a payment as complete without confirmation from the payment record.',
      ],
      'check' => Match the payment amount and donor before treating the donation as paid.',
    ],
    [
      'title' => 'Reports & Analytics',
      'icon' => 'fas fa-chart-bar text-primary',
      'purpose' => 'Review totals and produce printable or downloadable operational reports.',
      'steps' => [
        'Choose the report that matches the information you need, such as inventory, evacuation, relief, or donations.',
        'Review the totals on screen before choosing Print, Excel, or PDF.',
        'Use the exported report for coordination or filing, and return to the live page for the latest data.',
      ],
      'check' => Confirm the report date and scope before sharing it.',
    ],
    [
      'title' => 'Audit Trail',
      'icon' => 'fas fa-history text-secondary',
      'purpose' => 'Trace important account activity and changes made in the system.',
      'steps' => [
        'Search or review entries by user, action, or date when checking an activity.',
        'Open an entry to see the affected record and the recorded details.',
        'Use the trail to verify what happened before correcting a record or escalating an issue.',
      ],
      'check' => Treat the audit trail as a record of events; make corrections through the original page.',
    ],
    [
      'title' => 'My Profile',
      'icon' => 'fas fa-user text-dark',
      'purpose' => 'Review your account identity and the role that controls your available pages.',
      'steps' => [
        'Open your profile from the top navigation or the System menu.',
        'Check that your name, email, and role information are correct.',
        'Contact an administrator if your role or access does not match your assignment.',
      ],
      'check' => Never share your password or use another person\'s account.',
    ],
  ];
@endphp

@section('content')
<div class="row">
  <div class="col-lg-8">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-route mr-2"></i>{{ $workflow['title'] }}</h3>
      </div>
      <div class="card-body">
        <p class="lead mb-4">{{ $workflow['summary'] }}</p>
        <ol class="pl-4 mb-0">
          @foreach($workflow['steps'] as $step)
            <li class="mb-3 pl-2">{{ $step }}</li>
          @endforeach
        </ol>
      </div>
    </div>

    <div class="card card-outline card-secondary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-life-ring mr-2"></i>When you are unsure</h3>
      </div>
      <div class="card-body">
        <ul class="mb-0 pl-4">
          <li class="mb-2">Use the page that matches the real-world action. For example, record received items in Emergency Supplies before distributing them.</li>
          <li class="mb-2">Check the record details before saving. Accurate names, quantities, statuses, and dates make reports reliable.</li>
          <li class="mb-2">Do not use another person's account. Ask an administrator when your account cannot access a needed page.</li>
          <li>Sign out when you finish, especially on a shared computer.</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card card-outline card-info">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-compass mr-2"></i>Page guide</h3>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
          <div class="list-group-item"><strong>Dashboard</strong><small class="d-block text-muted">See current response activity at a glance.</small></div>
          <div class="list-group-item"><strong>Emergency Supplies</strong><small class="d-block text-muted">Track stock, status, and item releases.</small></div>
          <div class="list-group-item"><strong>Distributions</strong><small class="d-block text-muted">Record relief sent to an evacuation center.</small></div>
          <div class="list-group-item"><strong>Evacuation Centers</strong><small class="d-block text-muted">Manage centers, capacity, and evacuee check-ins.</small></div>
          <div class="list-group-item"><strong>Reports &amp; Analytics</strong><small class="d-block text-muted">Review, print, or export operational summaries.</small></div>
          <div class="list-group-item"><strong>My Profile</strong><small class="d-block text-muted">Review your account information.</small></div>
        </div>
      </div>
    </div>

    <div class="card bg-light">
      <div class="card-body">
        <h5><i class="fas fa-bell mr-2 text-warning"></i>Keep records current</h5>
        <p class="mb-0 small text-muted">Update a record as soon as the real-world action happens. This keeps dashboards, stock counts, and reports aligned for the next person.</p>
      </div>
    </div>
  </div>
</div>

<div class="card card-outline card-primary mt-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-book-open mr-2"></i>How to use each page</h3>
    <div class="card-tools"><span class="text-muted small">Select a page to see its steps</span></div>
  </div>
  <div class="card-body" id="page-instructions">
    @foreach($pageInstructions as $index => $instruction)
      <div class="border-bottom py-2">
        <button type="button" class="btn btn-link btn-block text-left px-0 text-dark" data-toggle="collapse" data-target="#page-instruction-{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="page-instruction-{{ $index }}">
          <i class="{{ $instruction['icon'] }} mr-2"></i><strong>{{ $instruction['title'] }}</strong>
          <i class="fas fa-chevron-down float-right mt-1 text-muted"></i>
        </button>
        <div id="page-instruction-{{ $index }}" class="collapse {{ $index === 0 ? 'show' : '' }}" data-parent="#page-instructions">
          <div class="pb-3 pl-4">
            <p class="mb-2">{{ $instruction['purpose'] }}</p>
            <ol class="pl-4 mb-2">
              @foreach($instruction['steps'] as $step)
                <li class="mb-1">{{ $step }}</li>
              @endforeach
            </ol>
            <p class="small text-muted mb-0"><strong>Before saving or sharing:</strong> {{ $instruction['check'] }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
