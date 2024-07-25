const backlogHeaders = [
    { headerName: "Código", headerRef: "thCode" },
    { headerName: "Región", headerRef: "thRegion" },
    { headerName: "Departamento", headerRef: "thDepartment" },
    { headerName: "Nombre Site", headerRef: "thSiteName" },
    { headerName: "Dirección", headerRef: "thAddress" },
    { headerName: "Distrito", headerRef: "thDistrict" },
    { headerName: "Tipo de Local", headerRef: "thLocalType" },
    { headerName: "Tipo de Actividad", headerRef: "thActivityType" },
    { headerName: "Task ID", headerRef: "thTaskID" },
    { headerName: "Sistema", headerRef: "thSystem" },
    { headerName: "Subsistema", headerRef: "thSubsystem" },
    { headerName: "Elemento", headerRef: "thElement" },
    { headerName: "Cantidad", headerRef: "thQuantity" },
    { headerName: "Fecha del Evento", headerRef: "thEventDate" },
    { headerName: "Fecha del Informe", headerRef: "thReportDate" },
    { headerName: "Retraso", headerRef: "thDelay" },
    { headerName: "Descripción del Evento y Detalle del Elemento", headerRef: "thEventDescElementDet" },
    { headerName: "Estado", headerRef: "thStatus" },
    { headerName: "Compromiso", headerRef: "thCommitment" },
    { headerName: "Fecha de Cierre", headerRef: "thClosureDate" },
    { headerName: "Responsable", headerRef: "thResponsible" },
    { headerName: "Responsable CICSA", headerRef: "thCicsaResponsible" },
    { headerName: "Criticidad", headerRef: "thCriticality" },
    { headerName: "Área Comproco", headerRef: "thComprocoArea" },
    { headerName: "Origen del Evento", headerRef: "thEventOrigin" },
    { headerName: "Reportado por", headerRef: "thReportedBy" },
    { headerName: "Informe", headerRef: "thReport" },
    { headerName: "Correo Enviado", headerRef: "thEmailSent" },
    { headerName: "Presupuesto/Cotización", headerRef: "thBudgetQuotation" },
    { headerName: "Requiere Presupuesto", headerRef: "thRequiresBudget" },
    { headerName: "Presupuesto", headerRef: "thBudget" },
];


const backlogItems = [
    { editable: true,  propType: "autocomplete" , propName: "backlog_site.site_id"},
    { editable: false,  propType: "text" , propName: "backlog_site.site_region"},
    { editable: false,  propType: "text" , propName: "backlog_site.department"},
    { editable: false,  propType: "text" , propName: "backlog_site.site_name"},
    { editable: false,  propType: "text" , propName: "backlog_site.address"},
    { editable: false,  propType: "text" , propName: "backlog_site.district"},
    { editable: false,  propType: "text" , propName: "backlog_site.site_type_label"},
    { 
        editable: true,  
        propType: "select", 
        propName: "activity_type",
        options: ['', 'CORRECTIVO', 'PREVENTIVO', 'MEJORAMIENTO', 'INSTALACIONES', 'OTRAS ACTIVIDADES']
    },
    { editable: true,  propType: "text" , propName: "task_id"},
    { 
        editable: true,  
        propType: "select" , 
        propName: "system",
        options: ['', 'DC', 'GE', 'SE', 'LMTLBT', 'AA', 'SPAT', 'TE', 'Torres', 'PanelesSolares', 'Transmisiones', 'Infraestructura', 'Otros'],
    },
    { 
        editable: true,  
        propType: "select" , 
        propName: "subsystem",
        advancedOptions: {
            DC: [
                "Rectificadores",
                "Inversores",
                "Baterias",
                "Tablero PDB",
            ],
            GE: [
                "Grupo electrógeno",
                "Tanque de combustible",
                "Tablero de Transferencia automática (TTA)/ Tablero de trasnferncia Manual (TTM)",
                "Sistema de enfriamiento",
            ],
            SE: [
                "Sub Estación Electrica"
            ],
            LMTLBT: [
                "Línea de Media Tensión",
                "Línea de Baja tensión"
            ],
            AA: [
                "Aire Acondicionado",
                "Ventilación"
            ],
            UPS: [
                "UPS"
            ],
            SPAT: [
                "Sistema de puesta a Tierra"
            ],
            TE: [
                "Tablero Electrico"
            ],
            Torres: [
                "Torres"
            ],
            PanelesSolares: [
                "Paneles Solares"
            ],
            Transmisiones: [
                "Acceso",
                "TX MW",
                "TX FO",
                "TX SAT",
            ],
            Infraestructura: [
                "INFRA"
            ],
            Otros: [
                "Otros"
            ]
        }
    },
    { editable: true,  propType: "text" , propName: "element"},
    { editable: true,  propType: "number" , propName: "quantity"},
    { editable: true,  propType: "date" , variantPropType: "date", propName: "event_date"},
    { editable: true,  propType: "date" , variantPropType: "date", propName: "report_date"},
    { editable: false,  propType: "number" , propName: "days_late"},
    { editable: true,  propType: "textarea" , propName: "event_element_description"},
    { editable: true,  propType: "select" , propName: "state"},
    { editable: true,  propType: "text" , propName: "commitment"},
    { editable: true,  propType: "date" , variantPropType: "date", propName: "c_start_date"},
    { editable: true,  propType: "date" , variantPropType: "date", propName: "c_end_date"},
    { editable: true,  propType: "text" , propName: "responsible"},
    { editable: true,  propType: "text" , propName: "criticity"},
    { editable: true,  propType: "text" , propName: "conproco_area"},
    { editable: true,  propType: "text" , propName: "origin_event"},
    { editable: true,  propType: "text" , propName: "report_by"},
    { editable: true,  propType: "text" , propName: "report"},
    { editable: true,  propType: "text" , propName: "email_send"},
    { editable: true,  propType: "number", variantPropType: "amount", propName: "budget"},
    { editable: true,  propType: "text" , propName: "budget_required"},
    { editable: true,  propType: "number", variantPropType: "amount" , propName: "budget_2"},
]

export {backlogHeaders, backlogItems}