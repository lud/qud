# Queues send jobs to the runner

And not the other way around. This is because we want the queues to be
able to generate a job, have it ran and then save it / delete it in
the same function.

This allows us to avoid to use this->currentJob, this->lastJob or
other state management warts.
