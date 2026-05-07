import { useEffect, useState } from 'react';
import Modal from 'react-bootstrap/Modal';

const ParticipanteModal = ({ dados, show, setShow, enviarDados }) => {

    const [editando, setEditando] = useState()

    const [form, setForm] = useState({
        nome: "",
        cpf: "",
        email: "",
        telefone: "",
        data_nascimento: ""
    })

    useEffect(() => {
        if (dados) {
            setEditando(true)
            setForm(dados)
        } else {
            setForm({
                nome: "",
                cpf: "",
                email: "",
                telefone: "",
                data_nascimento: ""
            })
            setEditando(false)
        }
    }, [dados, show])


    const handleChange = (e) => {
        const {name, value} = e.target;

        setForm((prev) => {...prev, [name]: value})
    }

    const handleSubmit = (e) => {
        e.preventDefault();

        const {id_participante, ...restoDados} = form

        enviarDados(...restoDados, editando)
    }

    return (
        <Modal show={show}>
            <Modal.Header>
                <Modal.Title>{editando ? 'Editar' : 'Novo'}</Modal.Title>
            </Modal.Header>
        </Modal>
    )
}


export default ParticipanteModal;