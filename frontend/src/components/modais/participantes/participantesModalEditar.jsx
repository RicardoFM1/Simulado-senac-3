import { useEffect, useState } from 'react';
import Stack from 'react-bootstrap/esm/Stack';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/esm/Button';

const ParticipanteModalEditar = ({ dados, show, setShow, enviarDados, handleClose, editando }) => {



    const [form, setForm] = useState({
        nome: "",
        cpf: "",
        email: "",
        telefone: "",
        data_nascimento: ""
    })

    useEffect(() => {
        if (dados) {
            editando(true)
            setForm(dados)
        } else {
            setForm({
                nome: "",
                cpf: "",
                email: "",
                telefone: "",
                data_nascimento: ""
            })
            editando(false)
        }
    }, [dados, show])


    const handleChange = (prev) => {
        const { name, value } = prev.target;
        if (!name) return;

        setForm((prev) = { ...prev, [name]: value })
    }

    const handleSubmit = (e) => {
        e.preventDefault();

        const { id_participante, ...restoDados } = form

        enviarDados(...restoDados, editando)
    }



    return (
        <Modal show={show} onHide={handleClose}>

            <Form>

                <Modal.Header closeButton>
                    <Modal.Title>{editando ? 'Editar' : 'Novo'}</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <Stack>
                        <Form.Label>Nome</Form.Label>
                        <Form.Control name='nome' onChange={handleChange} value={form.nome} />

                        <Form.Label>Cpf</Form.Label>
                        <Form.Control name='cpf' onChange={handleChange} value={form.cpf} />

                        <Form.Label>Email</Form.Label>
                        <Form.Control name='email' onChange={handleChange} value={form.email} />

                        <Form.Label>Telefone</Form.Label>
                        <Form.Control name='telefone' onChange={handleChange} value={form.telefone} />

                        <Form.Label>Data de nascimento</Form.Label>
                        <Form.Control name='data_nascimento' onChange={handleChange} value={form.data_nascimento} />
                    </Stack>
                </Modal.Body>

                <Modal.Footer>
                    <Button type='button' onClick={() => setShow(!show)} variant='light'>Cancelar</Button>
                    <Button type='submit'>Enviar</Button>
                </Modal.Footer>

            </Form>
        </Modal>
    )
}


export default ParticipanteModalEditar;