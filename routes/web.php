<?php

use App\Http\Controllers\AuthController;

use App\Livewire\Dashboard;
use App\Livewire\Onboarding\Onboarding;

use App\Livewire\Admin\BankAccounts\Overview as BankAccountsOverview;
use App\Livewire\Admin\BankAccounts\NewCompanyBankAccount;
use App\Livewire\Admin\BankAccounts\NewPersonalBankAccount;
use App\Livewire\Admin\BankAccounts\EditCompanyBankAccount;
use App\Livewire\Admin\BankAccounts\EditPersonalBankAccount;

use App\Livewire\Admin\CityRegions\Overview as CityRegionsOverview;
use App\Livewire\Admin\CityRegions\NewCityRegion;
use App\Livewire\Admin\CityRegions\Edit as EditCityRegion;

use App\Livewire\Admin\CoC\Overview as CoCOverview;
use App\Livewire\Admin\CoC\NewCoCType;
use App\Livewire\Admin\CoC\EditCoCType;

use App\Livewire\Admin\Companies\AddBankAccount as AddCompanyBankAccount;
use App\Livewire\Admin\Companies\AddEmployee;
use App\Livewire\Admin\Companies\AddItems;
use App\Livewire\Admin\Companies\AddPinConsole;
use App\Livewire\Admin\Companies\AddPlot;
use App\Livewire\Admin\Companies\EditCompany;
use App\Livewire\Admin\Companies\EditItem as EditCompanyItem;
use App\Livewire\Admin\Companies\NewCompany;
use App\Livewire\Admin\Companies\Overview as CompaniesOverview;

use App\Livewire\Admin\Countries\Overview as CountriesOverview;
use App\Livewire\Admin\Countries\NewCountry;
use App\Livewire\Admin\Countries\Edit as EditCountry;

use App\Livewire\Admin\Dashboard\Dashboard as AdminDashboard;

use App\Livewire\Admin\ItemsMenu\EditCategory;
use App\Livewire\Admin\ItemsMenu\EditItem;
use App\Livewire\Admin\ItemsMenu\EditItemGroup;
use App\Livewire\Admin\ItemsMenu\NewCategory;
use App\Livewire\Admin\ItemsMenu\NewItem;
use App\Livewire\Admin\ItemsMenu\NewItemGroup;
use App\Livewire\Admin\ItemsMenu\Overview as ItemsMenuOverview;

use App\Livewire\Admin\Languages\Overview as LanguagesOverview;
use App\Livewire\Admin\Languages\Edit as EditLanguage;
use App\Livewire\Admin\Languages\NewLanguage;

use App\Livewire\Admin\PinConsoles\Overview as PinConsolesOverview;
use App\Livewire\Admin\PinConsoles\NewPinConsole;
use App\Livewire\Admin\PinConsoles\Edit as EditPinConsole;

use App\Livewire\Admin\Players\AddBankAccount as AddPlayerBankAccount;
use App\Livewire\Admin\Players\AddChatColor;
use App\Livewire\Admin\Players\AddCompany;
use App\Livewire\Admin\Players\AddEmployer;
use App\Livewire\Admin\Players\AddPlot as AddPlotToPlayer;
use App\Livewire\Admin\Players\AddPrefix;
use App\Livewire\Admin\Players\EditPlayer;
use App\Livewire\Admin\Players\EditPrefix;
use App\Livewire\Admin\Players\Overview as PlayerOverview;

use App\Livewire\Admin\Plots\Overview as PlotsOverview;
use App\Livewire\Admin\Plots\NewPlot;
use App\Livewire\Admin\Plots\Edit as EditPlot;
use App\Livewire\Admin\Plots\AddMember;

use App\Livewire\Admin\RolesPerms\NewRole;
use App\Livewire\Admin\RolesPerms\EditRole;
use App\Livewire\Admin\RolesPerms\NewPermission;
use App\Livewire\Admin\RolesPerms\EditPermission;
use App\Livewire\Admin\RolesPerms\Overview as RolesPermsOverview;

use App\Livewire\Admin\Users\EditUser;
use App\Livewire\Admin\Users\Overview as UserOverview;

use App\Livewire\Admin\Wholesale\Overview as WholesaleOverview;
use App\Livewire\Admin\Wholesale\NewItem as NewWholesaleItem;
use App\Livewire\Admin\Wholesale\EditItem as EditWholesaleItem;

use App\Livewire\Company\CompanyDashboard;
use App\Livewire\Company\BankAccounts\BankaccountOverview;
use App\Livewire\Company\BankAccounts\ChooseBankaccount;
use App\Livewire\Company\Employees\Employees as CompanyEmployees;
use App\Livewire\Company\Inventory\EditStock;
use App\Livewire\Company\Inventory\StockOverview;
use App\Livewire\Company\Inventory\UpdateStock;
use App\Livewire\Company\Settings\Overview as CompanySettingsOverview;
use App\Livewire\Company\Sales\CreateSale;
use App\Livewire\Company\Sales\SaleDetails;
use App\Livewire\Company\Sales\SalesOverview;
use App\Livewire\Company\WholesaleOrders\ChooseCompany;
use App\Livewire\Company\WholesaleOrders\WholesaleOrders;
use App\Livewire\Company\WholesaleOrders\OrderDetails;

use App\Livewire\Profile\Overview as ProfileOverview;

use App\Livewire\Wholesale\ChooseCompany as WholesaleChooseCompany;
use App\Livewire\Wholesale\CollectOrder;
use App\Livewire\Wholesale\CompleteOrder;
use App\Livewire\Wholesale\CreateOrder;
use App\Livewire\Wholesale\OrderOverview;

use Illuminate\Support\Facades\Route;

// ! Guest Routes
Route::middleware('guest')->group(function() {
    // ? Login
    Route::get('/', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])
        ->middleware('throttle:5,1')
        ->name('login.post');

    // ? Register
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:6,1')
        ->name('register.post');
});

// ! Onboarding Routes
Route::middleware(['auth', 'redirect.onboarded'])->group(function() {
    // ? Onboarding
    Route::get('/onboarding', Onboarding::class)->name('onboarding.render');
});

// ! Authenticated Routes
Route::middleware(['auth', 'onboarding'])->group(function() {
    // ? Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ? Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard.render');
  
    // ? Profile
    Route::get('/profile', ProfileOverview::class)->name('profile.render');

    // ? Company
    Route::get('/company/choose', ChooseCompany::class)->name('company.choose.render');
    Route::middleware('employee_or_owner')->group(function() {
        Route::get('/company/{companyId}/dashboard', CompanyDashboard::class)->name('company.dashboard.render');
        Route::get('/company/{companyId}/employees', CompanyEmployees::class)->name('company.employees.render');
        Route::get('/company/{companyId}/sales', SalesOverview::class)->name('company.sales.render');
        Route::get('/company/{companyId}/sales/new', CreateSale::class)->name('company.sales.new.render');
        Route::get('/company/{companyId}/sales/{saleId}', SaleDetails::class)->name('company.sales.details.render');
        Route::get('/company/{companyId}/stock', StockOverview::class)->name('company.stock.render');
        Route::middleware('company_owner_or_manager')->group(function() {
            Route::get('/company/{companyId}/bank-account/{bankAccountId}', BankaccountOverview::class)->name('company.bank-account.render');
            Route::get('/company/{companyId}/choose-bank-account', ChooseBankaccount::class)->name('company.bank-accounts.render');
            Route::get('/company/{companyId}/orders', WholesaleOrders::class)->name('company.wholesale-orders.render');
            Route::get('/company/{companyId}/order/{orderId}', OrderDetails::class)->name('company.wholesale-orders.details.render');
            Route::get('/company/{companyId}/settings', CompanySettingsOverview::class)->name('company.settings.render');
            Route::get('/company/{companyId}/stock/edit/{itemId}', EditStock::class)->name('company.stock.edit.render');
            Route::get('/company/{companyId}/stock/update', UpdateStock::class)->name('company.stock.update.render');
        });
    });
  
    // ? Wholesale
    Route::middleware('permission:wholesale_order')->group(function() {
        Route::get('/wholesale/choose-company', WholesaleChooseCompany::class)->name('wholesale.choose-company');
        Route::get('/wholesale/create-order/{companyId}', CreateOrder::class)->middleware('company_owner_or_manager')->name('wholesale.create-order');
    });

    Route::middleware('permission:manage_wholesale_orders')->group(function() {
        Route::get('/wholesale/order-overview', OrderOverview::class)->name('wholesale.order-overview');
        Route::get('/wholesale/collect-order/{orderId}', CollectOrder::class)->name('wholesale.collect-order');
        Route::get('/wholesale/complete-order/{orderId}', CompleteOrder::class)->name('wholesale.complete-order');
    });
});

// ! Admin Routes
Route::middleware(['auth', 'onboarding'])->prefix('admin')->middleware('role:Superadmin')->group(function() {
    // ? Admin Dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard.render');

    // ? CoC
    Route::get('/coc', CoCOverview::class)->name('admin.coc.render');
    Route::get('/coc/new', NewCoCType::class)->name('admin.coc.new');
    Route::get('/coc/edit/{id}', EditCoCType::class)->name('admin.coc.edit');

    // ? Companies
    Route::middleware('permission:manage_companies')->group(function() {
        Route::get('/companies', CompaniesOverview::class)->name('admin.companies.render');
        Route::get('/companies/new', NewCompany::class)->name('admin.companies.new');
        Route::get('/companies/edit/{id}', EditCompany::class)->name('admin.companies.edit');
        Route::get('/companies/edit/{id}/add-bank-account', AddCompanyBankAccount::class)->name('admin.companies.add-bank-account');
        Route::get('/companies/edit/{id}/add-employee', AddEmployee::class)->name('admin.companies.add-employee');
        Route::get('/companies/edit/{id}/add-items', AddItems::class)->name('admin.companies.add-items');
        Route::get('/companies/edit/{id}/add-pin-console', AddPinConsole::class)->name('admin.companies.add-pin-console');
        Route::get('/companies/edit/{id}/add-plot', AddPlot::class)->name('admin.companies.add-plot');
        Route::get('/companies/edit/{companyId}/edit-item/{itemId}', EditCompanyItem::class)->name('admin.companies.edit-item');
    });

    // ? Permissions
    Route::middleware('permission:manage_permissions')->group(function() {
        Route::get('/permissions', RolesPermsOverview::class)->name('admin.roles-perms.render');
        Route::get('/roles/new', NewRole::class)->name('admin.roles-perms.role.new');
        Route::get('/roles/edit/{uuid}', EditRole::class)->name('admin.roles-perms.role.edit');
        Route::get('/permissions/new', NewPermission::class)->name('admin.roles-perms.permission.new');
        Route::get('/permissions/edit/{uuid}', EditPermission::class)->name('admin.roles-perms.permission.edit');
    });

    // ? Players
    Route::get('/players', PlayerOverview::class)->name('admin.players.render');
    Route::get('/players/edit/{uuid}', EditPlayer::class)->name('admin.players.edit');
    Route::get('/players/add-prefix/{uuid}', AddPrefix::class)->name('admin.players.add-prefix');
    Route::get('/players/edit-prefix/{uuid}/{id}', EditPrefix::class)->name('admin.players.edit-prefix');
    Route::get('/players/add-chat-color/{uuid}', AddChatColor::class)->name('admin.players.add-chat-color');
    Route::get('/players/add-bank-account/{uuid}', AddPlayerBankAccount::class)->name('admin.players.add-bank-account');
    Route::get('/players/add-plot/{uuid}', AddPlotToPlayer::class)->name('admin.players.add-plot');
    Route::get('/players/add-company/{uuid}', AddCompany::class)->name('admin.players.add-company');
    Route::get('/players/add-employer/{uuid}', AddEmployer::class)->name('admin.players.add-employer');

    // ? Languages
    Route::get('/languages', LanguagesOverview::class)->name('admin.languages.render');
    Route::get('/languages/edit/{id}', EditLanguage::class)->name('admin.languages.edit');
    Route::get('/languages/new', NewLanguage::class)->name('admin.languages.new');
  
    // ? Users
    Route::get('/users', UserOverview::class)->name('admin.users.render'); 
    Route::get('/users/edit/{uuid}', EditUser::class)->name('admin.users.edit');

    // ? Itemsmenu
    Route::get('/itemsmenu', ItemsMenuOverview::class)->name('admin.itemsmenu.render');
    Route::get('/categories/new', NewCategory::class)->name('admin.itemsmenu.category.new');
    Route::get('/categories/edit/{id}', EditCategory::class)->name('admin.itemsmenu.category.edit');
    Route::get('/items/new', NewItem::class)->name('admin.itemsmenu.item.new');
    Route::get('/items/edit/{id}', EditItem::class)->name('admin.itemsmenu.item.edit');
    Route::get('/item-groups/new', NewItemGroup::class)->name('admin.itemsmenu.item-group.new');
    Route::get('/item-groups/edit/{id}', EditItemGroup::class)->name('admin.itemsmenu.item-group.edit');

    // ? Countries
    Route::get('/countries', CountriesOverview::class)->name('admin.countries.render');
    Route::get('/countries/new', NewCountry::class)->name('admin.countries.new');
    Route::get('/countries/edit/{id}', EditCountry::class)->name('admin.countries.edit');

    // ? Countries
    Route::get('/city-regions', CityRegionsOverview::class)->name('admin.city-regions.render');
    Route::get('/city-regions/new', NewCityRegion::class)->name('admin.city-regions.new');
    Route::get('/city-regions/edit/{id}', EditCityRegion::class)->name('admin.city-regions.edit');

    // ? PIN Consoles
    Route::get('/pin-consoles', PinConsolesOverview::class)->name('admin.pin-consoles.render');
    Route::get('/pin-consoles/new', NewPinConsole::class)->name('admin.pin-consoles.new');
    Route::get('/pin-consoles/edit/{id}', EditPinConsole::class)->name('admin.pin-consoles.edit');

    // ? Bank Accounts
    Route::get('/bank-accounts', BankAccountsOverview::class)->name('admin.bank-accounts.render');
    Route::get('/bank-accounts/company/new', NewCompanyBankAccount::class)->name('admin.bank-accounts.company.new');
    Route::get('/bank-accounts/personal/new', NewPersonalBankAccount::class)->name('admin.bank-accounts.personal.new');
    Route::get('/bank-accounts/company/edit/{id}', EditCompanyBankAccount::class)->name('admin.bank-accounts.company.edit');
    Route::get('/bank-accounts/personal/edit/{id}', EditPersonalBankAccount::class)->name('admin.bank-accounts.personal.edit');

    // ? Plots
    Route::get('/plots', PlotsOverview::class)->name('admin.plots.render');
    Route::get('/plots/new', NewPlot::class)->name('admin.plots.new');
    Route::get('/plots/edit/{id}', EditPlot::class)->name('admin.plots.edit');
    Route::get('/plots/add-member/{id}', AddMember::class)->name('admin.plots.add-member');

    // ? Wholesale
    Route::get('/wholesale', WholesaleOverview::class)->name('admin.wholesale-items.render');
    Route::get('/wholesale/new', NewWholesaleItem::class)->name('admin.wholesale-items.new');
    Route::get('/wholesale/edit/{id}', EditWholesaleItem::class)->name('admin.wholesale-items.edit');
});