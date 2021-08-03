<?php

declare(strict_types=1);

namespace MetaDataTool\Enum;

use MyCLabs\Enum\Enum;

class KnownEntities extends Enum
{
    private const AccountancyAccountInvolvedAccounts = 'AccountancyAccountInvolvedAccounts';
    private const AssetsAssetGroups = 'AssetsAssetGroups';
    private const AssetsDepreciationMethods = 'AssetsDepreciationMethods';
    private const BudgetBudgets = 'BudgetBudgets';
    private const BulkFinancialTransactionLines = 'BulkFinancialTransactionLines';
    private const CashflowBanks = 'CashflowBanks';
    private const CashflowDirectDebitMandates = 'CashflowDirectDebitMandates';
    private const CashflowPaymentConditions = 'CashflowPaymentConditions';
    private const CashflowReceivables = 'CashflowReceivables';
    private const CrmAccountClassificationNames = 'CrmAccountClassificationNames';
    private const CrmAccountClassifications = 'CrmAccountClassifications';
    private const CrmAccounts = 'CrmAccounts';
    private const CrmAddresses = 'CrmAddresses';
    private const CrmAddressStates = 'CrmAddressStates';
    private const CrmBankAccounts = 'CrmBankAccounts';
    private const CrmContacts = 'CrmContacts';
    private const CrmOpportunities = 'CrmOpportunities';
    private const CrmQuotationLines = 'CrmQuotationLines';
    private const CrmQuotations = 'CrmQuotations';
    private const CrmReasonCodes = 'CrmReasonCodes';
    private const DocumentsDocumentAttachments = 'DocumentsDocumentAttachments';
    private const DocumentsDocumentCategories = 'DocumentsDocumentCategories';
    private const DocumentsDocumentFolders = 'DocumentsDocumentFolders';
    private const DocumentsDocumentTypes = 'DocumentsDocumentTypes';
    private const DocumentsDocuments = 'DocumentsDocuments';
    private const FinancialExchangeRates = 'FinancialExchangeRates';
    private const FinancialGLAccounts = 'FinancialGLAccounts';
    private const FinancialGLTransactionTypes = 'FinancialGLTransactionTypes';
    private const FinancialJournals = 'FinancialJournals';
    private const FinancialReportingBalance = 'FinancialReportingBalance';
    private const FinancialTransactionBankEntries = 'FinancialTransactionBankEntries';
    private const FinancialTransactionBankEntryLines = 'FinancialTransactionBankEntryLines';
    private const FinancialTransactionCashEntries = 'FinancialTransactionCashEntries';
    private const FinancialTransactionCashEntryLines = 'FinancialTransactionCashEntryLines';
    private const FinancialTransactionTransactionLines = 'FinancialTransactionTransactionLines';
    private const FinancialTransactionTransactions = 'FinancialTransactionTransactions';
    private const GeneralCurrencies = 'GeneralCurrencies';
    private const GeneralJournalEntryGeneralJournalEntries = 'GeneralJournalEntryGeneralJournalEntries';
    private const GeneralJournalEntryGeneralJournalEntryLines = 'GeneralJournalEntryGeneralJournalEntryLines';
    private const HrmAbsenceRegistrationTransactions = 'HrmAbsenceRegistrationTransactions';
    private const HrmAbsenceRegistrations = 'HrmAbsenceRegistrations';
    private const HrmCostcenters = 'HrmCostcenters';
    private const HrmCostunits = 'HrmCostunits';
    private const HrmDepartments = 'HrmDepartments';
    private const HrmDivisionClassNames = 'HrmDivisionClassNames';
    private const HrmDivisionClassValues = 'HrmDivisionClassValues';
    private const HrmDivisionClasses = 'HrmDivisionClasses';
    private const HrmDivisions = 'HrmDivisions';
    private const HrmJobGroups = 'HrmJobGroups';
    private const HrmJobTitles = 'HrmJobTitles';
    private const HrmLeaveBuildUpRegistrations = 'HrmLeaveBuildUpRegistrations';
    private const HrmLeaveRegistrations = 'HrmLeaveRegistrations';
    private const HrmSchedules = 'HrmSchedules';
    private const InventoryBatchNumbers = 'InventoryBatchNumbers';
    private const InventoryItemWarehousePlanningDetails = 'InventoryItemWarehousePlanningDetails';
    private const InventoryItemWarehouseStorageLocations = 'InventoryItemWarehouseStorageLocations';
    private const InventoryItemWarehouses = 'InventoryItemWarehouses';
    private const InventorySerialNumbers = 'InventorySerialNumbers';
    private const InventoryStockBatchNumbers = 'InventoryStockBatchNumbers';
    private const InventoryStockCountLines = 'InventoryStockCountLines';
    private const InventoryStockCounts = 'InventoryStockCounts';
    private const InventoryStockSerialNumbers = 'InventoryStockSerialNumbers';
    private const InventoryStorageLocations = 'InventoryStorageLocations';
    private const InventoryWarehouseTransferLines = 'InventoryWarehouseTransferLines';
    private const InventoryWarehouseTransfers = 'InventoryWarehouseTransfers';
    private const InventoryWarehouses = 'InventoryWarehouses';
    private const LogisticsItemGroups = 'LogisticsItemGroups';
    private const LogisticsItems = 'LogisticsItems';
//    private const LogisticItemVersions = 'LogisticItemVersions';
    private const LogisticsSalesItemPrices = 'LogisticsSalesItemPrices';
    private const LogisticsSupplierItem = 'LogisticsSupplierItem';
    private const LogisticsUnits = 'LogisticsUnits';
    private const MailboxMailboxes = 'MailboxMailboxes';
    private const MailboxMailMessageAttachments = 'MailboxMailMessageAttachments';
//    private const MailboxMailMessages = 'MailboxMailMessages';
    private const MailboxMailMessagesSent = 'MailboxMailMessagesSent';
    private const ManufacturingBillOfMaterialMaterials = 'ManufacturingBillOfMaterialMaterials';
    private const ManufacturingBillOfMaterialVersions = 'ManufacturingBillOfMaterialVersions';
    private const ManufacturingOperationResources = 'ManufacturingOperationResources';
    private const ManufacturingOperations = 'ManufacturingOperations';
    private const ManufacturingProductionAreas = 'ManufacturingProductionAreas';
    private const ManufacturingShopOrderMaterialPlans = 'ManufacturingShopOrderMaterialPlans';
    private const ManufacturingShopOrderRoutingStepPlans = 'ManufacturingShopOrderRoutingStepPlans';
    private const ManufacturingShopOrders = 'ManufacturingShopOrders';
    private const ManufacturingTimedTimeTransactions = 'ManufacturingTimedTimeTransactions';
    private const ManufacturingTimeTransactions = 'ManufacturingTimeTransactions';
    private const ManufacturingWorkcenters = 'ManufacturingWorkcenters';
    private const PayrollActiveEmployments = 'PayrollActiveEmployments';
    private const PayrollEmployees = 'PayrollEmployees';
    private const PayrollEmploymentContractFlexPhases = 'PayrollEmploymentContractFlexPhases';
    private const PayrollEmploymentContracts = 'PayrollEmploymentContracts';
    private const PayrollEmploymentEndReasons = 'PayrollEmploymentEndReasons';
    private const PayrollEmploymentOrganizations = 'PayrollEmploymentOrganizations';
    private const PayrollEmployments = 'PayrollEmployments';
    private const PayrollEmploymentSalaries = 'PayrollEmploymentSalaries';
    private const PayrollTaxEmploymentEndFlexCodes = 'PayrollTaxEmploymentEndFlexCodes';
    private const ProjectCostTransactions = 'ProjectCostTransactions';
    private const ProjectInvoiceTerms = 'ProjectInvoiceTerms';
    private const ProjectProjectBudgetTypes = 'ProjectProjectBudgetTypes';
    private const ProjectProjectHourBudgets = 'ProjectProjectHourBudgets';
    private const ProjectProjectPlanning = 'ProjectProjectPlanning';
    private const ProjectProjectPlanningRecurring = 'ProjectProjectPlanningRecurring';
    private const ProjectProjectRestrictionEmployees = 'ProjectProjectRestrictionEmployees';
    private const ProjectProjectRestrictionItems = 'ProjectProjectRestrictionItems';
    private const ProjectProjectRestrictionRebillings = 'ProjectProjectRestrictionRebillings';
    private const ProjectProjects = 'ProjectProjects';
    private const ProjectTimeCorrections = 'ProjectTimeCorrections';
    private const ProjectTimeTransactions = 'ProjectTimeTransactions';
    private const PurchaseEntryPurchaseEntries = 'PurchaseEntryPurchaseEntries';
    private const PurchaseEntryPurchaseEntryLines = 'PurchaseEntryPurchaseEntryLines';
    private const PurchaseOrderGoodsReceiptLines = 'PurchaseOrderGoodsReceiptLines';
    private const PurchaseOrderGoodsReceipts = 'PurchaseOrderGoodsReceipts';
    private const PurchaseOrderPurchaseOrderLines = 'PurchaseOrderPurchaseOrderLines';
    private const PurchaseOrderPurchaseOrders = 'PurchaseOrderPurchaseOrders';
    private const PurchasePurchaseInvoiceLines = 'PurchasePurchaseInvoiceLines';
    private const PurchasePurchaseInvoices = 'PurchasePurchaseInvoices';
    private const ReadFinancialOutstandingInvoicesOverview = ';ReadFinancialOutstandingInvoicesOverview';
    private const ReadFinancialPayablesList = 'ReadFinancialPayablesList';
    private const ReadFinancialProfitLossOverview = 'ReadFinancialProfitLossOverview';
    private const ReadFinancialReceivablesList = 'ReadFinancialReceivablesList';
    private const ReadFinancialRevenueList = 'ReadFinancialRevenueList';
    private const ReadLogisticsAccountItems = 'ReadLogisticsAccountItems';
    private const ReadLogisticsStockPosition = 'ReadLogisticsStockPosition';
    private const ReadProjectProjectWBSByProject = 'ReadProjectProjectWBSByProject';
    private const ReadProjectRecentCosts = 'ReadProjectRecentCosts';
    private const ReadProjectRecentHours = 'ReadProjectRecentHours';
    private const SalesEntrySalesEntries = 'SalesentrySalesEntries';
    private const SalesEntrySalesEntryLines = 'SalesentrySalesEntryLines';
    private const SalesInvoiceInvoiceSalesOrders = 'SalesInvoiceInvoiceSalesOrders';
    private const SalesInvoiceLayouts = 'SalesInvoiceLayouts';
    private const SalesInvoicePrintedSalesInvoices = 'SalesinvoicePrintedSalesInvoices';
    private const SalesInvoiceSalesInvoiceLines = 'SalesinvoiceSalesInvoiceLines';
    private const SalesInvoiceSalesInvoices = 'SalesInvoiceSalesInvoices';
    private const SalesInvoiceSalesOrderID = 'SalesInvoiceSalesOrderID';
    private const SalesOrderGoodsDeliveries = 'SalesOrderGoodsDeliveries';
    private const SalesOrderGoodsDeliveryLines = 'SalesOrderGoodsDeliveryLines';
    private const SalesOrderPlannedSalesReturnLines = 'SalesOrderPlannedSalesReturnLines';
    private const SalesOrderPrintedSalesOrders = 'SalesorderPrintedSalesOrders';
    private const SalesOrderSalesOrderLines = 'SalesOrderSalesOrderLines';
    private const SalesOrderSalesOrders = 'SalesOrderSalesOrders';
    private const SalesPriceLists = 'SalesPriceLists';
    private const SalesShippingMethods = 'SalesShippingMethods';
    private const SubscriptionSubscriptionLines = 'SubscriptionSubscriptionLines';
    private const SubscriptionSubscriptionLineTypes = 'SubscriptionSubscriptionLineTypes';
    private const SubscriptionSubscriptionReasonCodes = 'SubscriptionSubscriptionReasonCodes';
    private const SubscriptionSubscriptionRestrictionEmployees = 'SubscriptionSubscriptionRestrictionEmployees';
    private const SubscriptionSubscriptionRestrictionItems = 'SubscriptionSubscriptionRestrictionItems';
    private const SubscriptionSubscriptionTypes = 'SubscriptionSubscriptionTypes';
    private const SubscriptionSubscriptions = 'SubscriptionSubscriptions';
    private const SystemSystemDivisions = 'SystemSystemDivisions';
    private const SystemSystemMe = 'SystemSystemMe';
    private const UsersUserRoles = 'UsersUserRoles';
    private const VatVatCodes = 'VatVATCodes';
    private const VatVatPercentages = 'VatVatPercentages';
    private const WebhooksWebhookSubscriptions = 'WebhooksWebhookSubscriptions';
}
