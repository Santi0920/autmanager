// condicionNit.js
// function esCondicionNit(codigoAutorizacion) {
//     var condiciontalento = [$codigoAutorizacion === '10M'];

//     var condiciongeneral = condiciontalento + condicionsistemas
//     return codigoAutorizacion === '10M' || codigoAutorizacion === '11E'|| codigoAutorizacion=== '11F'|| codigoAutorizacion==='11M'|| codigoAutorizacion==='11N'|| codigoAutorizacion=== '11O'|| codigoAutorizacion==='11P'|| codigoAutorizacion==='11Q' || codigoAutorizacion=== '11R'|| codigoAutorizacion==='11S'|| codigoAutorizacion==='11T'  || codigoAutorizacion=== '11U'|| codigoAutorizacion==='11V'|| codigoAutorizacion==='11W' || codigoAutorizacion=== '11X'|| codigoAutorizacion==='11Y'|| codigoAutorizacion==='11Z'  ||codigoAutorizacion === '11AB'||  codigoAutorizacion=== '11AD'|| codigoAutorizacion==='11AE'|| codigoAutorizacion==='11AF'|| codigoAutorizacion==='11AG' || codigoAutorizacion === '10J' || codigoAutorizacion === '10G' || codigoAutorizacion === '10N' ||codigoAutorizacion === '10O' ||codigoAutorizacion === '10P' || codigoAutorizacion === '2300D' || codigoAutorizacion === '2400A' || codigoAutorizacion === '2400B' ||codigoAutorizacion === '1500A' || codigoAutorizacion==='19A'|| codigoAutorizacion === '19J' || codigoAutorizacion === '19D'|| codigoAutorizacion === '19E' || codigoAutorizacion === '1400A' || codigoAutorizacion === '1500C';
// }


// function condicionparamostrarNit(codigoAutorizacion) {
//     return codigoAutorizacion !== '10M' && codigoAutorizacion !== '11E'&& codigoAutorizacion!== '11F'&& codigoAutorizacion!=='11M'&& codigoAutorizacion!=='11N'&& codigoAutorizacion!== '11O'&& codigoAutorizacion!=='11P'&& codigoAutorizacion!=='11Q' && codigoAutorizacion!== '11R'&& codigoAutorizacion!=='11S'&& codigoAutorizacion!=='11T'  && codigoAutorizacion!== '11U'&& codigoAutorizacion!=='11V'&& codigoAutorizacion!=='11W' && codigoAutorizacion!== '11X'&& codigoAutorizacion!=='11Y'&& codigoAutorizacion!=='11Z'  &&codigoAutorizacion !== '11AB'&&  codigoAutorizacion!== '11AD'&& codigoAutorizacion!=='11AE'&& codigoAutorizacion!=='11AF'&& codigoAutorizacion!=='11AG' && codigoAutorizacion !== '10J' && codigoAutorizacion !== '10G' && codigoAutorizacion !== '10N' &&codigoAutorizacion !== '10O' &&codigoAutorizacion !== '10P' && codigoAutorizacion !== '2300D' && codigoAutorizacion !== '2400A' && codigoAutorizacion !=='2400B' &&codigoAutorizacion !== '1500A' && codigoAutorizacion!=='19A' && codigoAutorizacion !== '19J' && codigoAutorizacion !== '19D' && codigoAutorizacion !== '19E' && codigoAutorizacion !== '1400A' &&  codigoAutorizacion !== '1500C';

// }



function esCondicionNit(codigoAutorizacion) {
    var condicionTalento = [
        codigoAutorizacion === "10A" ||
        codigoAutorizacion === "10B" ||
        codigoAutorizacion === "10C" ||
        codigoAutorizacion === "10D" ||
        codigoAutorizacion === "10E" ||
        codigoAutorizacion === "10F" ||
        codigoAutorizacion === "10G" ||
        codigoAutorizacion === "10H" ||
        codigoAutorizacion === "10I" ||
        codigoAutorizacion === "10J" ||
        codigoAutorizacion === "10K" ||
        codigoAutorizacion === "10L"
    ];

    var condicionCoordinacion = [
        codigoAutorizacion === "11K" ||
        codigoAutorizacion === "11L" ||
        codigoAutorizacion === "11M" ||
        codigoAutorizacion === "11N" ||
        codigoAutorizacion === "11O" ||
        codigoAutorizacion === "11P" ||
        codigoAutorizacion === "11Q" ||
        codigoAutorizacion === "11R"

    ];

    var condicionSistemas = [
        codigoAutorizacion === "19B" ||
        codigoAutorizacion === "19C"
        // codigoAutorizacion === "19D" ||
        // codigoAutorizacion === "19E" ||
        // codigoAutorizacion === "19F" ||
        // codigoAutorizacion === "19G" ||
        // codigoAutorizacion === "19H"
    ];

    var condicionGlobal= [
        codigoAutorizacion === '0A' ||
        codigoAutorizacion === '0B' ||
        codigoAutorizacion ==='0F'  ||
        codigoAutorizacion ==='0J' ||
        codigoAutorizacion ==='0K'

    ]

    var condicionJurdicoZn = [codigoAutorizacion === '2250C'];

    var condicionJurdicoZc = [codigoAutorizacion === '2150C'];

    var condicionJurdicoZs = [codigoAutorizacion === '2350C'];

    var condicionTesoreria = [
        codigoAutorizacion === "15A" || codigoAutorizacion === "15C"
    ];

    var condicionMeredian = [
        codigoAutorizacion === "24A"
    ];

    var condicionFundacion = [codigoAutorizacion === "14A"];

    var condicionSeguros = [codigoAutorizacion === "23A"];

    var condicionConsejo = [];

    var condicionGeneral = condicionTalento.some(condicion => condicion) ||
                           condicionSistemas.some(condicion => condicion) ||
                           condicionCoordinacion.some(condicion => condicion) ||
                           condicionJurdicoZn.some(condicion => condicion) ||
                           condicionJurdicoZc.some(condicion => condicion) ||
                           condicionJurdicoZs.some(condicion => condicion) ||
                           condicionTesoreria.some(condicion => condicion) ||
                           condicionMeredian.some(condicion => condicion) ||
                           condicionFundacion.some(condicion => condicion) ||
                           condicionSeguros.some(condicion => condicion) ||
                           condicionConsejo.some(condicion => condicion) ||
                           condicionGlobal.some(condicion => condicion);

    return condicionGeneral;
}




  function condicionparamostrarNit(codigoAutorizacion) {
    var condicionTalento = [
        codigoAutorizacion != "10A" &&
        codigoAutorizacion != "10B" &&
        codigoAutorizacion != "10C" &&
        codigoAutorizacion != "10D" &&
        codigoAutorizacion != "10E" &&
        codigoAutorizacion != "10F" &&
        codigoAutorizacion != "10G" &&
        codigoAutorizacion != "10H" &&
        codigoAutorizacion != "10I" &&
        codigoAutorizacion != "10J" &&
        codigoAutorizacion != "10K" &&
        codigoAutorizacion != "10L"
    ];

    var condicionCoordinacion = [
      // codigoAutorizacion != "11M" &&
      //   codigoAutorizacion != "11N" &&
      //   codigoAutorizacion != "11O" &&
      //   codigoAutorizacion != "11P" &&
      //   codigoAutorizacion != "11Q" &&
      //   codigoAutorizacion != "11R" &&
      //   codigoAutorizacion != "11S" &&
      //   codigoAutorizacion != "11T" &&
      //   codigoAutorizacion != "11U" &&
      //   codigoAutorizacion != "11V" &&
      //   codigoAutorizacion != "11X",

        codigoAutorizacion != "11K" ||
        codigoAutorizacion != "11L" ||
        codigoAutorizacion != "11M" ||
        codigoAutorizacion != "11N" ||
        codigoAutorizacion != "11O" ||
        codigoAutorizacion != "11P" ||
        codigoAutorizacion != "11Q" ||
        codigoAutorizacion != "11R"

    ];

    var condicionSistemas = [
      codigoAutorizacion != "19B" &&
        codigoAutorizacion != "19C" ,
    ];

    var condicionGlobal= [
        codigoAutorizacion != '0A' ||
        codigoAutorizacion != '0B' ||
        codigoAutorizacion != '0F' ||
        codigoAutorizacion != '0J' ||
        codigoAutorizacion != '0K'
         ]

    // codigoAutorizacion != '2250A' &&
    var condicionJurdicoZn = [ codigoAutorizacion != '2250C'];

    // codigoAutorizacion != '2150A' &&
    var condicionJurdicoZc = [ codigoAutorizacion != '2250C'];
    // codigoAutorizacion != '2350A' &&
    var condicionJurdicoZs = [codigoAutorizacion != '2250C'];

    var condicionTesoreria = [
      codigoAutorizacion != "15A" && codigoAutorizacion != "15C",
    ];

    var condicionMeredian = [
      codigoAutorizacion != "24A",
    ];

    var condicionFundacion = [codigoAutorizacion != "14A"];

    var condicionSeguros = [codigoAutorizacion != "23A"];

    var condicionConsejo = [];

    var condicionGeneral = condicionTalento.some(condicion => condicion) ||
                           condicionSistemas.some(condicion => condicion) ||
                           condicionCoordinacion.some(condicion => condicion) ||
                           condicionJurdicoZn.some(condicion => condicion) ||
                           condicionJurdicoZc.some(condicion => condicion) ||
                           condicionJurdicoZs.some(condicion => condicion) ||
                           condicionTesoreria.some(condicion => condicion) ||
                           condicionMeredian.some(condicion => condicion) ||
                           condicionFundacion.some(condicion => condicion) ||
                           condicionSeguros.some(condicion => condicion) ||
                           condicionConsejo.some(condicion => condicion)  ||
                           condicionGlobal.some(condicion => condicion);

    return condicionGeneral;
  }




