<?php

/*
    |--------------------------------------------------------------------------
    | Quest Types
    |--------------------------------------------------------------------------
    */
return [
    "0" => [
        "name" => "💵 RECARGA",
        "subActivityType" => [
            "0" => "FIRST_CONTACT [OFF]", //FIRST_CONTACT
            "1" => "ACC_FIRST_PAY [OFF]",
            "2" => "ONE_OFF_PAY [OFF]",
            "3" => "🪙 ACUMULADA", //ACCUMULATIVE_PAY
            "5" => "🪙 NOVO SERVIDOR", //NEWGAMEBENIFIT
            "6" => "🪙 RETORNO", //PAY_RETURN
            "7" => "ONE_OFF_IN_TIME [OFF]",
        ]
    ],
    "1" => [
        "name" => "🤑 CONSUMO",
        "subActivityType" => [
            "0" => "ONE_OFF_CONSUME [OFF]",
            "1" => "💸 RETORNO",
            "2" => "SPECIFIC_COUNT_CONSUME [OFF]"
        ]
    ],
    "2" => [
        "name" => "🔁 TROCA",
        "subActivityType" => [
            "0" => "❓ NORMAL"
        ]
    ],
    "4" => [
        "name" => "💍 CASAMENTO",
        "subActivityType" => [
            "2" => "HOLD_WEDDING [OFF]"
        ]
    ],
    "5" => [
        "name" => "❓ RECEIVE_ACTIVITY",
        "subActivityType" => [
            "1" => "USER_ID_RECEIVE",
            "2" => "DAILY_RECEIVE",
            "3" => "SEND_GIFT"
        ]
    ],
    "6" => [
        "name" => "🤝 GUILD",
        "subActivityType" => [
            "1" => "💪 CONTRIBUIÇÃO"
        ]
    ],
    "7" => [
        "name" => "👑 HERÓI",
        "subActivityType" => [
            "0" => "💪 FORÇA",
            "1" => "💾 NÍVEL"
        ]
    ],
    "8" => [
        "name" => "🥾 EQUIPAMENTO",
        "subActivityType" => [
            "0" => "💪 FORTALECIMENTO"
        ]
    ],
    "10" => [
        "name" => "❓ GROUP_PURCHASE_ACTIVITY",
        "subActivityType" => [
            "0" => "NORMAL_GROUP_PURCHASE"
        ]
    ],
    "11" => [
        "name" => "❓ FLOWER_GIVING_ACTIVITY",
        "subActivityType" => [
            "0" => "ROSE",
            "1" => "MUM",
            "2" => "CARNETION"
        ]
    ],
    "12" => [
        "name" => "🥇 RANKING CONSUMO",
        "subActivityType" => [
            "0" => "💸 RANKING CONSUMO"
        ],
        "remain2" => [
            "name" => "Q. Min. De Consumo"
        ]
    ],
    "13" => [
        "name" => "❓ FOOD_ACTIVITYS",
        "subActivityType" => [
            "0" => "FOOD_ONLINETIME",
            "1" => "FOOD_ACTIVITY"
        ]
    ],
    "14" => [
        "name" => "🐴 MONTARIA",
        "subActivityType" => [
            "0" => "💾 NÍVEL",
            "1" => "👊 SKILL"
        ]
    ],
    "15" => [
        "name" => "🥳 ATIVIDADES",
        "subActivityType" => [
            "1" => "💾 NÍVEL",
            "2" => "💪 FORTALECIMENTO",
            "3" => "🥛 COMPOSIÇÃO",
            "4" => "💣 FORÇA DE COMBATE",
            "5" => "🧋 PÉROLA NÍVEL",
            "6" => "🪀 TOTEM",
            "7" => "🫖 PRÁTICA",
            "8" => "🎴 CARTA NÍVEL",
            "9" => "🟣 ESPÍRITO DE LUTA",
            "10" => "🐴 MONTARIA",
            "11" => "🐱 PET NÍVEL",
            "12" => "CARNIVAL_ROOKIE_TWO"
        ]
    ],
    "16" => [
        "name" => "👑 VIP",
        "subActivityType" => [
            "0" => "💾 NÍVEL"
        ]
    ],
    "17" => [
        "name" => "🐱 PET",
        "subActivityType" => [
            "0" => "💾 NÍVEL"
        ]
    ],
    "18" => [
        "name" => "❓ DAILY_GIFT",
        "subActivityType" => [
            "0" => "DAILY_GIFT_USE",
            "1" => "DAILY_GIFT_CARD",
            "2" => "DAILY_GIFT_BEAD",
            "3" => "DAILY_GIFT_STONE",
            "4" => "DAILY_GIFT_PLANT"
        ]
    ],
    "19" => [
        "name" => "❓ VENDA DE MUNIÇÃO",
    ],
    "24" => [
        "name" => "❓ MAPA DO TESOURO",
    ],
    "25" => [
        "name" => "❓ OFICINA DE FORJA",
    ],
    "31" => [
        "name" => "❓ ENTRADA DIÁRIA",
    ],
    "61" => [
        "name" => "🥇 RANKING RECARGA",
        "subActivityType" => [
            "0" => "💸 RANKING RECARGA"
        ],
        "remain2" => [
            "name" => "Q. Min. De Consumo"
        ]
    ],
];
