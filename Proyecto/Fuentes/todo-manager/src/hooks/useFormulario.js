import { useState } from "react" // Importamos el hook useState

/**
 * Comprueba los inputs de un formulario
 * 
 * @param {*} initialState Objeto referente al estado inicial de los inputs
 * 
 * @returns Los inputs, la función manejadora del OnChange y el método reset()
 */
export const useFormulario = (initialState = {}) => { // Paso el objeto initialState como prop a este hook

    // Hook con un array de los inputs que se quieren validar y la función para establecerlos
    const [inputs, setInputs] = useState(initialState); // Se llaman inputs porque es un nombre más genérico y se puede usar el hook para más formularios

    /**
     * Función para controlar el evento onChange
     * @param {*} e Evento onChange
     */
    const handleChange = e => { // Paso la función que controla el evento onChange a este hook
        const {name, value, checked, type} = e.target;
    
        setInputs(
            // Uso las llaves para indicar que estoy devolviendo un objeto
            // Hago una ternaria para que devuelva el atributo checked cuando se reciba de un checkbox
            {...inputs, [name]: type === "checkbox" ? checked : value}
        );
    };

    /**
     * Reinicia los campos del formulario gracias a initialState
     */
    const reset = () => {
        setInputs(initialState);
    };
    

    // Devuelvo un array con los inputs a validar y las funciones para controlar el onChange y reiniciar el form
    return [inputs, handleChange, reset];
}